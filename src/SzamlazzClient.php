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

    public function getTaxPayer(string $taxPayerId): SzamlaAgentResponse
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
            $postFieldsLength = strlen($request->postFields);

            $response = $this->sendPost(
                static::API_URL,
                [
                    'cert' => $this->getCertificationFilePath(),
                    'headers' => [
                        "Content-Type: multipart/form-data; boundary=$request->delim",
                        "Content-Length: $postFieldsLength",
                    ],
                    'body' => $request->postFields,
                    'timeout' => $request::REQUEST_TIMEOUT,
                    'cookies' => new CookieJar(),
                ]
            );

            return $response;
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
