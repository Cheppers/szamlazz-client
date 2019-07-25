<?php

declare(strict_types=1);

namespace Cheppers\SzamlazzClient;

use Cheppers\SzamlazzClient\DataType\SzamlaAgentRequest;
use Cheppers\SzamlazzClient\DataType\TaxPayer;
use Cheppers\SzamlazzClient\Utils\SzamlaAgentUtil;
use GuzzleHttp\ClientInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;

class SzamlazzClient
{
    const API_URL = 'szamla/';

    /**
     * @var \GuzzleHttp\ClientInterface
     */
    protected $client;

    /**
     * @var string
     */
    public $username = '';

    /**
     * @var string
     */
    public $password = '';

    /**
     * @var string
     */
    protected $keychain = '';

    /**
     * @var bool
     */
    public $downloadPdf = true;

    /**
     * @var int
     */
    public $downloadCopiesCount = 0;

    protected $logger;

    /**
     * @var int
     */
    public $responseType = 1;

    /**
     * @var \Psr\Http\Message\ResponseInterface
     */
    protected $response;

    /**
     * {@inheritdoc}
     */
    public function getResponse()
    {
        return $this->response;
    }

    protected $baseUri = 'https://www.szamlazz.hu';

    protected static $propertyMapping = [
        'username' => 'felhasznalo',
        'password' => 'jelszo',
        'keychain' => 'kulcstartojelszo',
        'downloadPdf' => 'pdfLetoltes',
        'downloadCopiesCount' => 'szamlaLetoltesPld',
        'valaszVerzio' => 'valaszVerzio',
        'aggregator' => 'aggregator',
    ];

    public function setUp(string $username, string $password)
    {
        $this->username = $username;
        $this->password = $password;

        return $this;
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

    public function __construct(ClientInterface $client, LoggerInterface $logger)
    {
        $this->client = $client;
        $this->logger = $logger;
    }

    /**
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \Exception
     */
    public function getTaxPayer(string $taxPayerId)
    {
        $response = $this->sendSzamlaAgentRequest('getTaxPayer', new TaxPayer($taxPayerId));

        libxml_use_internal_errors(true);

        $data = new \DOMDocument();
        $data->loadXML($response->getBody()->getContents());

        if (!SzamlaAgentUtil::isResponseValid($data)) {
            $this->logXmlErrors();
        }
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
