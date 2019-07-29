<?php

declare(strict_types=1);

namespace Cheppers\SzamlazzClient;

use Cheppers\SzamlazzClient\DataType\Invoice;
use Cheppers\SzamlazzClient\DataType\Response\TaxPayerResponse;
use Cheppers\SzamlazzClient\DataType\Settings;
use Cheppers\SzamlazzClient\DataType\SzamlaAgentRequest;
use Cheppers\SzamlazzClient\DataType\TaxPayer;
use Cheppers\SzamlazzClient\Utils\SzamlaAgentUtil;
use GuzzleHttp\ClientInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;

class SzamlazzClient
{
    const API_URL = 'szamla/';

    /**
     * @var \GuzzleHttp\ClientInterface
     */
    protected $client;

    /**
     * @var \Cheppers\SzamlazzClient\DataType\Settings
     */
    public $settings;

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

    protected static $propertyMapping = [
        'username' => 'felhasznalo',
        'password' => 'jelszo',
        'keychain' => 'kulcstartojelszo',
        'downloadPdf' => 'pdfLetoltes',
        'downloadCopiesCount' => 'szamlaLetoltesPld',
        'valaszVerzio' => 'valaszVerzio',
        'aggregator' => 'aggregator',
    ];

    public function __construct(ClientInterface $client, LoggerInterface $logger, Settings $settings)
    {
        $this->client = $client;
        $this->logger = $logger;
        $this->settings = Settings::__set_state($settings);
    }

    /**
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \Exception
     */
    public function getTaxPayer(string $taxpayerId): ?TaxPayerResponse
    {
        $response = $this->sendSzamlaAgentRequest('getTaxPayer', TaxPayer::__set_state(['taxpayerId' => $taxpayerId]));

        libxml_use_internal_errors(true);

        $doc = new \DOMDocument();
        $doc->loadXML($response->getBody()->getContents());

        if (!SzamlaAgentUtil::isResponseValid($doc)) {
            $this->logXmlErrors();
            throw new SzamlazzClientException(SzamlazzClientException::RESPONSE_TYPE_NOT_VALID);
        }

        /** @var \DOMElement $root */
        $root = $doc->getElementsByTagName('QueryTaxpayerResponse')->item(0);

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
        $response = $this->sendSzamlaAgentRequest('generateInvoice', Invoice::__set_state($data));
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
    public function sendSzamlaAgentRequest(string $type, object $entity): ResponseInterface
    {
        $request = new SzamlaAgentRequest($this, $type, $entity);

        try {
            $xml = $request->buildXmlData();

            $response = $this->sendPost(
                self::API_URL,
                [
                    'multipart' => [
                        [
                            'name'     => $request->fileName,
                            'contents' => fopen('data:text/plain,' . urlencode($xml), 'rb'),
                        ],
                    ],
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
        if (!SzamlaAgentUtil::isResponseValid($doc)) {
            $this->logXmlErrors();

            return false;
        }

        return true;
    }
}
