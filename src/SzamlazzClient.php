<?php

declare(strict_types=1);

namespace Cheppers\SzamlazzClient;

use Cheppers\SzamlazzClient\DataType\SzamlaAgentRequest;
use Cheppers\SzamlazzClient\DataType\SzamlaAgentResponse;
use Cheppers\SzamlazzClient\DataType\TaxPayer;
use Cheppers\SzamlazzClient\Utils\SzamlaAgentUtil;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Cookie\CookieJar;

class SzamlazzClient
{

    const XML_FILE_SAVE_PATH = './xmls';

    const CHARSET = 'utf-8';

    const API_URL = 'https://www.szamlazz.hu/szamla/';

    const CERTIFICATION_PATH = './cert';

    const CERTIFICATION_FILENAME = 'cacert.pem';

    /**
     * @var \GuzzleHttp\ClientInterface
     */
    protected $client;

    /**
     * @var string
     */
    protected $username = '';

    /**
     * @var string
     */
    protected $password = '';

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
     * @var string
     */
    public $cookieFileName = 'cookie.txt';

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

    protected $baseUri = 'http://www.szamlazz.hu';

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

    public function getTaxPayer(string $taxPayerId)
    {
        $this->sendSzamlaAgentRequest('getTaxPayer', new TaxPayer($taxPayerId));
    }

    protected function getUri($path)
    {
        return $this->getBaseUri() . "/$path";
    }

    /**
     * @return $this
     */
    protected function sendGet($path, array $options = [])
    {
        return $this->sendRequest('GET', $path, $options);
    }

    /**
     * @return $this
     */
    protected function sendPost($path, array $options = [])
    {
        return $this->sendRequest('POST', $path, $options);
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return $this
     */
    protected function sendRequest($method, $path, array $options = [])
    {
        $uri = $this->getUri($path);
        $this->response = $this->client->request($method, $uri, $options);

        return $this;
    }

    public function getCertificationFilePath(): string
    {
        return SzamlaAgentUtil::getAbsPath(static::CERTIFICATION_PATH, static::CERTIFICATION_FILENAME);
    }

    public function sendSzamlaAgentRequest(string $type, object $entity)
    {
        $request = new SzamlaAgentRequest($this, $type, $entity);

        try {
            if (!isset($_SESSION)) {
                session_start();
            }

            $request->init();
            $request->buildXmlData();
            $request->buildQuery();

            $response = $this->sendPost(
                static::API_URL,
                [
                    'multipart' => [
                        'name'     => $request->fileName,
                        'contents' => $request->postFields,
                    ],
                ]
            );

            return $response;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function buildXmlData(SzamlaAgentRequest $request)
    {
        $settings = ['felhasznalo', 'jelszo'];

        switch ($request->getXmlName()) {
            case $request::XML_SCHEMA_CREATE_INVOICE:
                $data = $this->buildFieldsData($request, array_merge($settings, [
                    'eszamla',
                    'kulcstartojelszo',
                    'szamlaLetoltes',
                    'szamlaLetoltesPld',
                    'valaszVerzio',
                    'aggregator'
                ]));
                break;
            case $request::XML_SCHEMA_DELETE_PROFORMA:
                $data = $this->buildFieldsData($request, $settings);
                break;
            case $request::XML_SCHEMA_CREATE_REVERSE_INVOICE:
                $data = $this->buildFieldsData($request, array_merge($settings, [
                    'eszamla',
                    'kulcstartojelszo',
                    'szamlaLetoltes',
                    'szamlaLetoltesPld',
                    'aggregator',
                    'valaszVerzio'
                ]));
                break;
            case $request::XML_SCHEMA_PAY_INVOICE:
                $data = $this->buildFieldsData($request, array_merge($settings, [
                    'szamlaszam',
                    'additiv',
                    'aggregator',
                    'valaszVerzio'
                ]));
                break;
            case $request::XML_SCHEMA_REQUEST_INVOICE_PDF:
                $data = $this->buildFieldsData($request, array_merge($settings, [
                    'szamlaszam',
                    'rendelesSzam',
                    'valaszVerzio'
                ]));
                break;
            case $request::XML_SCHEMA_CREATE_RECEIPT:
            case $request::XML_SCHEMA_CREATE_REVERSE_RECEIPT:
            case $request::XML_SCHEMA_GET_RECEIPT:
                $data = $this->buildFieldsData($request, array_merge($settings, ['pdfLetoltes']));
                break;
            case $request::XML_SCHEMA_SEND_RECEIPT:
            case $request::XML_SCHEMA_TAXPAYER:
                $data = $this->buildFieldsData($request, $settings);
                break;
            default:
                throw new SzamlazzClientException(
                    SzamlazzClientException::XML_SCHEMA_TYPE_NOT_EXISTS
                    . ": {$request->getXmlName()}"
                );
        }
        return $data;
    }

    /**
     * Összeállítja és visszaadja az adott mezőkhöz tartozó adatokat
     *
     * @param SzamlaAgentRequest $request
     * @param array $fields
     *
     * @return array
     * @throws \Cheppers\SzamlazzClient\SzamlazzClientException
     */
    public function buildFieldsData(SzamlaAgentRequest $request, array $fields)
    {
        $data = [];

        foreach ($fields as $key) {
            switch ($key) {
                case 'felhasznalo':
                    $value = $this->username;
                    break;
                case 'jelszo':
                    $value = $this->password;
                    break;
                case 'kulcstartojelszo':
                    $value = $this->keychain;
                    break;
                case 'szamlaLetoltes':
                case 'pdfLetoltes':
                    $value = $this->downloadPdf;
                    break;
                case 'szamlaLetoltesPld':
                    $value = $this->downloadCopiesCount;
                    break;
                case 'valaszVerzio':
                    $value = $this->responseType;
                    break;
                default:
                    throw new SzamlazzClientException(SzamlazzClientException::XML_KEY_NOT_EXISTS . ": {$key}");
            }

            if (isset($value)) {
                $data[$key] = $value;
            }
        }
        return $data;
    }
}
