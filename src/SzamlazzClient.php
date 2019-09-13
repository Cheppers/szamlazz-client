<?php

declare(strict_types = 1);

namespace Cheppers\SzamlazzClient;

use Cheppers\SzamlazzClient\DataType\GenerateInvoice;
use Cheppers\SzamlazzClient\DataType\GenerateReverseInvoice;
use Cheppers\SzamlazzClient\DataType\Response\InvoiceResponse;
use Cheppers\SzamlazzClient\DataType\Response\ReverseInvoiceResponse;
use Cheppers\SzamlazzClient\DataType\Response\TaxPayerResponse;
use Cheppers\SzamlazzClient\DataType\QueryTaxpayer;
use DOMDocument;
use Exception;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;

class SzamlazzClient implements SzamlazzClientInterface
{

    const REQUEST_TIMEOUT = 30;

    const ERROR_CODE_INVALID_RESPONSE_CONTENT_TYPE = 53;

    const ERROR_CODE_XML_INVALID = 56;

    const ERROR_CODE_XML_SCHEMA_MISMATCH = 57;

    /**
     * @var \GuzzleHttp\ClientInterface
     */
    protected $client;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @var string
     */
    protected $baseUri = 'https://www.szamlazz.hu/szamla/';

    public function __construct(ClientInterface $client, LoggerInterface $logger)
    {
        $this->client = $client;
        $this->logger = $logger;
    }

    /**
     * @return string
     */
    public function getBaseUri(): string
    {
        return $this->baseUri;
    }

    /**
     * @return $this
     */
    public function setBaseUri(string $baseUri)
    {
        $this->baseUri = $baseUri;

        return $this;
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */
    public function getTaxpayer(QueryTaxpayer $queryTaxpayer): ?TaxPayerResponse
    {
        $response = $this->sendSzamlaAgentRequest(
            $queryTaxpayer->fileName,
            $queryTaxpayer->buildXmlString()
        );

        $doc = new DOMDocument();
        $this
            ->validateResponseContentType(['application/octet-stream'], $response->getHeader('Content-Type'))
            ->validateXml($doc->loadXML($response->getBody()->getContents()))
            ->validateDocumentBySchemaNames($doc, ['invoiceApi', 'invoiceApiError']);

        /** @var \DOMElement $root */
        $root = $doc
            ->getElementsByTagName('QueryTaxpayerResponse')
            ->item(0);
        $taxpayer = TaxPayerResponse::__set_state($root);

        if ($taxpayer->errorCode || $taxpayer->funcCode === 'ERROR') {
            throw new Exception($taxpayer->message, (int) $taxpayer->errorCode);
        }

        if (!$taxpayer->taxpayerName) {
            // Not found.
            return null;
        }

        return $taxpayer;
    }

    /**
     * @throws \Exception
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function generateInvoice(GenerateInvoice $invoice): InvoiceResponse
    {
        $response = $this->sendSzamlaAgentRequest(
            $invoice->fileName,
            $invoice->buildXmlString()
        );

        $doc = new DOMDocument();
        $this
            ->validateResponseContentType(['application/octet-stream'], $response->getHeader('Content-Type'))
            ->validateXml($doc->loadXML($response->getBody()->getContents()))
            ->validateDocumentBySchemaNames($doc, ['xmlszamlavalasz']);

        /** @var \DOMElement $root */
        $root = $doc->getElementsByTagName('xmlszamlavalasz')->item(0);
        $invoiceResponse = InvoiceResponse::__set_state($root);

        if ($invoiceResponse->success !== true) {
            throw  new Exception($invoiceResponse->errorMessage, $invoiceResponse->errorCode);
        }

        return $invoiceResponse;
    }

    /**
     * @throws Exception
     * @throws GuzzleException
     */
    public function generateReverseInvoice(GenerateReverseInvoice $reverseInvoice): ReverseInvoiceResponse
    {
        $response = $this->sendSzamlaAgentRequest(
            $reverseInvoice->fileName,
            $reverseInvoice->buildXmlString()
        );

        $this->validateResponseContentType(
            [
                'application/pdf',
                'text/html;charset=UTF-8',
            ],
            $response->getHeader('Content-Type')
        );

        $reverseInvoiceResponse = ReverseInvoiceResponse::__set_state($response);
        if ($reverseInvoiceResponse->errorCode) {
            throw  new Exception($reverseInvoiceResponse->errorMessage, $reverseInvoiceResponse->errorCode);
        }

        return $reverseInvoiceResponse;
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \ReflectionException
     */
    public function sendSzamlaAgentRequest(string $fileName, string $xml): ResponseInterface
    {
        return $this->sendPost(
            $this->getBaseUri(),
            [
                'multipart' => [
                    [
                        'name' => $fileName,
                        'contents' => fopen('data:text/plain,' . urlencode($xml), 'rb'),
                    ],
                ],
                'timeout' => static::REQUEST_TIMEOUT,
            ]
        );
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function sendPost(string $path, array $options = []): ResponseInterface
    {
        return $this->client->request('POST', $path, $options);
    }

    /**
     * @param string[] $expected
     * @param string[] $actual
     *
     * @return $this
     * @throws \Exception
     */
    protected function validateResponseContentType(array $expected, array $actual)
    {
        if (!array_intersect($expected, $actual)) {
            throw new Exception(
                'Invalid response content type ' . implode(',', $actual),
                static::ERROR_CODE_INVALID_RESPONSE_CONTENT_TYPE
            );
        }

        return $this;
    }

    /**
     * @return $this
     * @throws \Exception
     */
    protected function validateXml(bool $isValidXml)
    {
        if (!$isValidXml) {
            throw new Exception('Response body is not a valid XML', static::ERROR_CODE_XML_INVALID);
        }

        return $this;
    }

    /**
     * @param \DOMDocument $doc
     * @param string[] $schemaNames
     *
     * @return $this
     * @throws \Exception
     */
    protected function validateDocumentBySchemaNames(DOMDocument $doc, array $schemaNames)
    {
        $allErrors = Utils::validateDocumentBySchemaNames($doc, $schemaNames);
        if (!$allErrors) {
            return $this;
        }

        foreach ($allErrors as $errors) {
            Utils::logXmlErrors($this->logger, $errors);
        }

        throw new Exception(
            "Document doesn't match to any of the following schemas: " . implode(', ', $schemaNames),
            static::ERROR_CODE_XML_SCHEMA_MISMATCH
        );
    }
}
