<?php

declare(strict_types = 1);

namespace Cheppers\SzamlazzClient;

use Cheppers\SzamlazzClient\DataType\Response\InvoiceResponse;
use Cheppers\SzamlazzClient\DataType\Response\TaxPayerResponse;
use Cheppers\SzamlazzClient\DataType\SzamlaAgentRequest;
use DOMDocument;
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
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \Exception
     */
    public function getTaxPayer(string $apiKey, string $taxpayerId): ?TaxPayerResponse
    {
        $requestData = new SzamlaAgentRequest();
        $requestData->setFields(
            'getTaxPayer',
            [
                'settings' => [
                    'apiKey' => $apiKey,
                ],
                'taxpayerId' => $taxpayerId,
            ]
        );

        $response = $this->sendSzamlaAgentRequest($requestData);
        // @todo Check response Content-type.
        $docResponse = new DOMDocument();

        // @todo Error handling.
        $docResponse->loadXML($response->getBody()->getContents());

        $errorsSuccess = Utils::validateTaxpayerSuccessResponse($docResponse);
        if ($errorsSuccess) {
            $errorsFail = Utils::validateTaxpayerErrorResponse($docResponse);
            if ($errorsFail) {
                Utils::logXmlErrors($this->logger, $errorsFail);

                throw  new \Exception('Invalid response', 1);
            }
        }

        /** @var \DOMElement $root */
        $root = $docResponse
            ->getElementsByTagName('QueryTaxpayerResponse')
            ->item(0);

        $taxpayer = TaxPayerResponse::__set_state($root);

        if ($taxpayer->errorCode || $taxpayer->funcCode === 'ERROR') {
            throw new \Exception($taxpayer->message, (int) $taxpayer->errorCode);
        }

        if (!$taxpayer->taxpayerName) {
            // Not found.
            return null;
        }

        return $taxpayer;
    }

    /**
     * @throws \Exception
     */
    public function generateInvoice(array $data)
    {
        $requestData = new SzamlaAgentRequest();
        $requestData->setFields('generateInvoice', $data);

        $response = $this->sendSzamlaAgentRequest($requestData);
        $docResponse = new DOMDocument();
        $docResponse->loadXML($response->getBody()->getContents());

        $errors = Utils::validateInvoiceResponse($docResponse);
        if ($errors) {
            Utils::logXmlErrors($this->logger, $errors);

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

    /**
     * @throws \Exception
     */
    public function sendSzamlaAgentRequest(SzamlaAgentRequest $requestData): ResponseInterface
    {
        return $this->sendPost(
            self::API_URL,
            [
                'multipart' => [
                    [
                        'name' => $requestData->fileName,
                        'contents' => fopen('data:text/plain,' . urlencode($requestData->buildXml()), 'rb'),
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

    protected function sendGet($path, array $options = []): ResponseInterface
    {
        return $this->sendRequest('GET', $path, $options);
    }

    protected function sendPost($path, array $options = []): ResponseInterface
    {
        return $this->sendRequest('POST', $path, $options);
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
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
