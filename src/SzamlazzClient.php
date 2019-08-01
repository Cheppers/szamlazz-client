<?php

declare(strict_types = 1);

namespace Cheppers\SzamlazzClient;

use Cheppers\SzamlazzClient\DataType\GenerateInvoice;
use Cheppers\SzamlazzClient\DataType\GenerateReverseInvoice;
use Cheppers\SzamlazzClient\DataType\Response\InvoiceResponse;
use Cheppers\SzamlazzClient\DataType\Response\ReverseInvoiceResponse;
use Cheppers\SzamlazzClient\DataType\Response\TaxPayerResponse;
use Cheppers\SzamlazzClient\DataType\RequestBase;
use Cheppers\SzamlazzClient\DataType\QueryTaxpayer;
use DOMDocument;
use DOMElement;
use Exception;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use ReflectionException;

class SzamlazzClient
{

    const API_URL = 'szamla/';

    const REQUEST_TIMEOUT = 30;

    /**
     * @var ClientInterface
     */
    protected $client;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var string
     */
    protected $baseUri = 'https://www.szamlazz.hu';

    public function __construct(
        ClientInterface $client,
        LoggerInterface $logger
    ) {
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
     * @throws SzamlazzClientException
     * @throws GuzzleException
     * @throws ReflectionException
     * @throws Exception
     */
    public function getTaxpayer(QueryTaxpayer $queryTaxpayer): ?TaxPayerResponse
    {
        $requestData = $queryTaxpayer->buildXmlString();

        $response = $this->sendSzamlaAgentRequest($queryTaxpayer->fileName, $requestData);

        if ($response->getHeader('Content-Type')[0] !== 'application/octet-stream') {
            throw  new Exception('Invalid response content type', 53);
        }

        $docResponse = new DOMDocument();

        if ($docResponse->loadXML($response->getBody()->getContents()) === false) {
            throw  new Exception('Response XML cannot be loaded', 57);
        }

        $errorsSuccess = Utils::validateTaxpayerSuccessResponse($docResponse);
        if ($errorsSuccess) {
            $errorsFail = Utils::validateTaxpayerErrorResponse($docResponse);
            if ($errorsFail) {
                Utils::logXmlErrors($this->logger, $errorsFail);

                throw  new Exception('Invalid response', 57);
            }
        }

        /** @var DOMElement $root */
        $root = $docResponse
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
     * @throws Exception
     * @throws GuzzleException
     */
    public function generateInvoice(GenerateInvoice $invoice): ?InvoiceResponse
    {
        $requestData = $invoice->buildXmlString();

        $response = $this->sendSzamlaAgentRequest($invoice->fileName, $requestData);
        $docResponse = new DOMDocument();
        $docResponse->loadXML($response->getBody()->getContents());

        $errors = Utils::validateInvoiceResponse($docResponse);
        if ($errors) {
            Utils::logXmlErrors($this->logger, $errors);

            throw new SzamlazzClientException(SzamlazzClientException::RESPONSE_TYPE_NOT_VALID);
        }

        /** @var DOMElement $root */
        $root = $docResponse->getElementsByTagName('xmlszamlavalasz')->item(0);

        $invoiceResponse = InvoiceResponse::__set_state($root);

        if (!$invoiceResponse->success === true) {
            throw new SzamlazzClientException(
                SzamlazzClientException::INVOICE_GENERATE_FAILED . ' ' . $invoiceResponse->errorMessage
            );
        }

        return $invoiceResponse;
    }

    /**
     * @throws Exception
     * @throws GuzzleException
     */
    public function generateReverseInvoice(GenerateReverseInvoice $reverseInvoice): ?ReverseInvoiceResponse
    {
        $requestData = $reverseInvoice->buildXmlString();

        $response = $this->sendSzamlaAgentRequest($reverseInvoice->fileName, $requestData);

        if ($response->getHeader('Content-Type')[0] !== 'application/pdf') {
            throw  new Exception('Invalid response content type', 53);
        }

        return ReverseInvoiceResponse::__set_state($response);
    }

    /**
     * @throws SzamlazzClientException
     * @throws GuzzleException
     * @throws ReflectionException
     */
    public function sendSzamlaAgentRequest(string $fileName, string $xml): ResponseInterface
    {
        return $this->sendPost(
            self::API_URL,
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

    protected function getUri($path)
    {
        return $this->getBaseUri() . "/$path";
    }

    /**
     * @throws GuzzleException
     */
    protected function sendGet(string $path, array $options = []): ResponseInterface
    {
        return $this->sendRequest('GET', $path, $options);
    }

    /**
     * @throws GuzzleException
     */
    protected function sendPost(string $path, array $options = []): ResponseInterface
    {
        return $this->sendRequest('POST', $path, $options);
    }

    /**
     * @throws GuzzleException
     */
    protected function sendRequest(
        $method,
        $path,
        array $options = []
    ): ResponseInterface {
        $uri = $this->getUri($path);

        return $this->client->request($method, $uri, $options);
    }
}
