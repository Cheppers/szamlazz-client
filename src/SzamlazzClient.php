<?php

declare(strict_types=1);

namespace Cheppers\SzamlazzClient;

use Cheppers\SzamlazzClient\DataType\Response\InvoiceResponse;
use Cheppers\SzamlazzClient\DataType\Response\TaxPayerResponse;
use Cheppers\SzamlazzClient\DataType\SzamlaAgentRequest;
use Cheppers\SzamlazzClient\Utils\SzamlaAgentUtil;
use GuzzleHttp\ClientInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;

class SzamlazzClient
{
    const API_URL = 'szamla/';

    const REQUEST_TIMEOUT = 30;

    /**
     * @var \GuzzleHttp\ClientInterface
     */
    protected $client;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    protected $baseUri = 'https://www.szamlazz.hu';

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

    public function __construct(ClientInterface $client, LoggerInterface $logger)
    {
        $this->client = $client;
        $this->logger = $logger;
    }

    /**
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \Exception
     */
    public function getTaxPayer(array $data): ?TaxPayerResponse
    {
        $request = new SzamlaAgentRequest('getTaxPayer', $data);
        $response = $this->sendSzamlaAgentRequest($request);
        $docResponse = new \DOMDocument();
        $docResponse->loadXML($response->getBody()->getContents());

        if (!SzamlaAgentUtil::isTaxpayerResponseValid($docResponse)) {
            libxml_use_internal_errors(true);
            $this->logXmlErrors();
            throw new SzamlazzClientException(SzamlazzClientException::RESPONSE_TYPE_NOT_VALID);
        }

        /** @var \DOMElement $root */
        $root = $docResponse->getElementsByTagName('QueryTaxpayerResponse')->item(0);

        $taxpayer = TaxPayerResponse::__set_state($root);

        if (!$taxpayer->taxpayerName && !$taxpayer->address) {
            throw new SzamlazzClientException(SzamlazzClientException::TAXPAYER_NOT_EXIST);
        }

        return $taxpayer;
    }

    /**
     * @throws \Exception
     */
    public function generateInvoice(array $data)
    {
        $request = new SzamlaAgentRequest('generateInvoice', $data);
        $response = $this->sendSzamlaAgentRequest($request);
        $docResponse = new \DOMDocument();
        $docResponse->loadXML($response->getBody()->getContents());

        if (!SzamlaAgentUtil::isInvoiceResponseValid($docResponse)) {
            libxml_use_internal_errors(true);
            $this->logXmlErrors();
            throw new SzamlazzClientException(SzamlazzClientException::RESPONSE_TYPE_NOT_VALID);
        }

        /** @var \DOMElement $root */
        $root = $docResponse->getElementsByTagName('xmlszamlavalasz')->item(0);

        $invoiceResponse = InvoiceResponse::__set_state($root);

        if (!$invoiceResponse->success === true) {
            throw new SzamlazzClientException(
                SzamlazzClientException::INVOICE_GENERATE_FAILED . $invoiceResponse->errorMessage
            );
        }

        return $invoiceResponse;
    }

    protected function getUri($path)
    {
        return $this->getBaseUri() . "/$path";
    }

    /**
     * @return \Psr\Http\Message\MessageInterface
     */
    protected function sendGet($path, array $options = [])
    {
        return $this->sendRequest('GET', $path, $options);
    }

    /**
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function sendPost($path, array $options = [])
    {
        return $this->sendRequest('POST', $path, $options);
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function sendRequest($method, $path, array $options = [])
    {
        $uri = $this->getUri($path);

        return $this->client->request($method, $uri, $options);
    }

    /**
     * @throws \Exception
     */
    public function sendSzamlaAgentRequest(SzamlaAgentRequest $request): ResponseInterface
    {
        try {
            $response = $this->sendPost(
                self::API_URL,
                [
                    'multipart' => [
                        [
                            'name'     => $request->fileName,
                            'contents' => fopen('data:text/plain,' . urlencode($request->buildXml()), 'rb'),
                        ],
                    ],
                    'timeout' => static::REQUEST_TIMEOUT,
                ]
            );

            return $response;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    protected function logXmlErrors()
    {
        $errors = libxml_get_errors();
        foreach ($errors as $error) {
            $this->logger->error($error->message);
        }
        libxml_clear_errors();
    }

    public function validateResponse(\DOMDocument $doc)
    {
        if (!SzamlaAgentUtil::isTaxpayerResponseValid($doc)) {
            $this->logXmlErrors();

            return false;
        }

        return true;
    }
}
