<?php

declare(strict_types=1);

namespace Cheppers\SzamlazzClient;

use Cheppers\SzamlazzClient\DataType\SzamlaAgentRequest;
use Cheppers\SzamlazzClient\DataType\TaxPayer;
use Cheppers\SzamlazzClient\Utils\SzamlaAgentUtil;
use GuzzleHttp\ClientInterface;
use Psr\Http\Message\ResponseInterface;

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

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
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
            return $this->xmlDisplayErrors();
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

    protected function xmlDisplayError($error)
    {
        $return = "<br/>\n";
        switch ($error->level) {
            case LIBXML_ERR_WARNING:
                $return .= "<b>Warning $error->code</b>: ";
                break;
            case LIBXML_ERR_ERROR:
                $return .= "<b>Error $error->code</b>: ";
                break;
            case LIBXML_ERR_FATAL:
                $return .= "<b>Fatal Error $error->code</b>: ";
                break;
        }
        $return .= trim($error->message);
        if ($error->file) {
            $return .=    " in <b>$error->file</b>";
        }
        $return .= " on line <b>$error->line</b>\n";

        return $return;
    }

    protected function xmlDisplayErrors()
    {
        $errors = libxml_get_errors();
        $error = '';
        foreach ($errors as $error) {
            $error .= $this->xmlDisplayError($error);
        }
        libxml_clear_errors();

        return $error;
    }

    public function validateResponse(\DOMDocument $doc)
    {
        if (!SzamlaAgentUtil::isResponseValid($doc)) {
            return $this->xmlDisplayErrors();
        }

        return true;
    }
}
