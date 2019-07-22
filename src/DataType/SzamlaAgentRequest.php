<?php

declare(strict_types=1);

namespace Cheppers\SzamlazzClient\DataType;

use Cheppers\SzamlazzClient\SzamlazzClient;
use Cheppers\SzamlazzClient\SzamlazzClientException;
use Cheppers\SzamlazzClient\Utils\SzamlaAgentUtil;

class SzamlaAgentRequest
{

    const HTTP_OK = 200;

    const CRLF = "\r\n";

    const XML_BASE_URL = 'http://www.szamlazz.hu/';

    const SESSION_CONNECTION_TYPE = 'connectionType';

    const REQUEST_TIMEOUT = 30;

    const CALL_METHOD_LEGACY = 1;

    const CALL_METHOD_CURL = 2;

    const CALL_METHOD_AUTO = 3;

    const XML_SCHEMA_CREATE_INVOICE = 'xmlszamla';

    const XML_SCHEMA_CREATE_REVERSE_INVOICE = 'xmlszamlast';

    const XML_SCHEMA_PAY_INVOICE = 'xmlszamlakifiz';

    const XML_SCHEMA_REQUEST_INVOICE_PDF = 'xmlszamlapdf';

    const XML_SCHEMA_CREATE_RECEIPT = 'xmlnyugtacreate';

    const XML_SCHEMA_CREATE_REVERSE_RECEIPT = 'xmlnyugtast';

    const XML_SCHEMA_SEND_RECEIPT = 'xmlnyugtasend';

    const XML_SCHEMA_GET_RECEIPT = 'xmlnyugtaget';

    const XML_SCHEMA_TAXPAYER = 'xmltaxpayer';

    const XML_SCHEMA_DELETE_PROFORMA = 'xmlszamladbkdel';

    /**
     * @var \Cheppers\SzamlazzClient\SzamlazzClient
     */
    public $szamlazzClient;

    /**
     * @var string
     */
    public $type;

    /**
     * @var object
     */
    public $entity;

    /**
     * @var string
     */
    public $xmlData;

    /**
     * @var string
     */
    public $xmlName;

    /**
     * @var string
     */
    public $xsdDir;

    /**
     * @var string
     */
    public $fileName;

    /**
     * @var string
     */
    public $delim;

    /**
     * @var string
     */
    public $postFields;

    public function __construct(SzamlazzClient $szamlazzClient, string $type, object $entity)
    {
        $this->szamlazzClient = $szamlazzClient;
        $this->type = $type;
        $this->entity = $entity;
    }

    /**
     * @throws \Cheppers\SzamlazzClient\SzamlazzClientException
     * @throws \ReflectionException
     */
    public function buildXmlData(): void
    {
        $this->setXmlFileData($this->type);

        $xmlData = $this->entity->buildXmlData($this);

        $xml = new \SimpleXMLElement($this->getXmlBase());
        $this->arrayToXML($xmlData, $xml);

        $result = SzamlaAgentUtil::checkValidXml($xml->saveXML());
        if (!empty($result)) {
            throw new SzamlazzClientException(
                SzamlazzClientException::XML_NOT_VALID . " a {$result[0]->line}. sorban: {$result[0]->message}. "
            );
        }

        $formatXml = SzamlaAgentUtil::formatXml($xml);
        $this->xmlData = $formatXml->saveXML();

        $this->createXmlFile($formatXml);
    }

    protected function arrayToXML(array $xmlData, \SimpleXMLElement &$xmlFields)
    {
        foreach ($xmlData as $key => $value) {
            if (!is_array($value)) {
                continue;
            }
            $fieldKey = $key;
            if (mb_strpos($key, "item") !== false) {
                $fieldKey = 'tetel';
            }
            if (mb_strpos($key, "note") !== false) {
                $fieldKey = 'kifizetes';
            }
            $subNode = $xmlFields->addChild("$fieldKey");
            $this->arrayToXML($value, $subNode);
        }
    }

    /**
     * @throws \Cheppers\SzamlazzClient\SzamlazzClientException
     * @throws \ReflectionException
     */
    protected function createXmlFile(\DOMDocument $xml)
    {
        $fileName = SzamlaAgentUtil::getXmlFileName('request', $this->xmlName, $this->entity);
        if ($xml->save($fileName) === false) {
            throw new SzamlazzClientException(SzamlazzClientException::FILE_CREATION_FAILED);
        }
    }

    protected function getXmlBase(): string
    {
        $xmlName = $this->xmlName;
        $namespace = $this->getXmlNs($xmlName);

        $queryData  = '<?xml version="1.0" encoding="UTF-8"?>'.PHP_EOL;
        $queryData .= "<$xmlName xmlns=$namespace xmlns:xsi='http://www.w3.org/2001/XMLSchema-instance' xsi:schemaLocation=$this->getSchemaLocation($xmlName)>"
            . PHP_EOL;
        $queryData .= "</$xmlName>" . static::CRLF;

        return $queryData;
    }

    protected function getSchemaLocation(string $xmlName): string
    {
        return static::XML_BASE_URL . "szamla/{$xmlName} http://www.szamlazz.hu/szamla/docs/xsds/{$this->getXsdDir()}/{$xmlName}.xsd";
    }

    public function getXmlNs(string $xmlName): string
    {
        return self::XML_BASE_URL . "{$xmlName}";
    }

    public function buildQuery()
    {
        $this->delim = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 16);
        $fileName = $this->fileName;

        $queryData  = '--' . $this->delim . static::CRLF;
        $queryData .= "Content-Disposition: form-data; name= $fileName; filename=$fileName" . static::CRLF;
        $queryData .= 'Content-Type: text/xml' . static::CRLF . static::CRLF;
        $queryData .= $this->xmlData . static::CRLF;
        $queryData .= "--" . $this->delim . "--" . static::CRLF;

        $this->postFields = $queryData;
    }

    public function init()
    {
        header('Content-type: text/html; charset=' . SzamlazzClient::CHARSET);
        header('PHP-version: ' . PHP_VERSION);
    }

    /**
     * @throws \Cheppers\SzamlazzClient\SzamlazzClientException
     */
    protected function setXmlFileData(string $type)
    {
        switch ($type) {
            case 'generateProforma':
            case 'generateInvoice':
            case 'generatePrePaymentInvoice':
            case 'generateFinalInvoice':
            case 'generateCorrectiveInvoice':
            case 'generateDeliveryNote':
                $fileName = 'action-xmlagentxmlfile';
                $xmlName  = self::XML_SCHEMA_CREATE_INVOICE;
                $xsdDir  = 'agent';
                break;
            case 'generateReverseInvoice':
                $fileName = 'action-szamla_agent_st';
                $xmlName  = self::XML_SCHEMA_CREATE_REVERSE_INVOICE;
                $xsdDir  = 'agentst';
                break;
            case 'payInvoice':
                $fileName = 'action-szamla_agent_kifiz';
                $xmlName  = self::XML_SCHEMA_PAY_INVOICE;
                $xsdDir  = 'agentkifiz';
                break;
            case 'requestInvoicePDF':
                $fileName =  'action-szamla_agent_pdf';
                $xmlName  = self::XML_SCHEMA_REQUEST_INVOICE_PDF;
                $xsdDir  = 'agentpdf';
                break;
            case 'generateReceipt':
                $fileName = 'action-szamla_agent_nyugta_create';
                $xmlName  = self::XML_SCHEMA_CREATE_RECEIPT;
                $xsdDir  = 'nyugtacreate';
                break;
            case 'generateReverseReceipt':
                $fileName = 'action-szamla_agent_nyugta_storno';
                $xmlName  = self::XML_SCHEMA_CREATE_REVERSE_RECEIPT;
                $xsdDir  = 'nyugtast';
                break;
            case 'sendReceipt':
                $fileName = 'action-szamla_agent_nyugta_send';
                $xmlName  = self::XML_SCHEMA_SEND_RECEIPT;
                $xsdDir  = 'nyugtasend';
                break;
            case 'requestReceiptPDF':
                $fileName = 'action-szamla_agent_nyugta_get';
                $xmlName  = self::XML_SCHEMA_GET_RECEIPT;
                $xsdDir   = 'nyugtaget';
                break;
            case 'getTaxPayer':
                $fileName = 'action-szamla_agent_taxpayer';
                $xmlName  = self::XML_SCHEMA_TAXPAYER;
                $xsdDir   = 'taxpayer';
                break;
            case 'deleteProforma':
                $fileName = 'action-szamla_agent_dijbekero_torlese';
                $xmlName  = self::XML_SCHEMA_DELETE_PROFORMA;
                $xsdDir   = 'dijbekerodel';
                break;
            default:
                throw new SzamlazzClientException(SzamlazzClientException::REQUEST_TYPE_NOT_EXISTS . ": {$type}");
        }

        $this->fileName = $fileName;
        $this->xmlName = $xmlName;
        $this->xsdDir = $xsdDir;
    }

    /**
     * Számla Agent kérés küldése a szamlazz.hu felé
     *
     * Először megpróbáljuk CURL-el elküldeni a kérést.
     * Ha nem sikerül, akkor átváltunk a legacy módra.
     *
     * @return array
     * @throws \Exception
     */
    public function send()
    {
        try {
            if (!isset($_SESSION)) {
                session_start();
            }

            $this->init();
            $this->buildXmlData();
            $this->buildQuery();

            $method = $this->szamlazzClient->getCallMethod();
            switch ($method) {
                case self::CALL_METHOD_AUTO:
                    $response = $this->checkConnection();
                    break;
                case self::CALL_METHOD_CURL:
                    $response = $this->makeCurlCall();
                    break;
                case self::CALL_METHOD_LEGACY:
                    $response = $this->makeLegacyCall();
                    break;
                default:
                    throw new SzamlaAgentException(SzamlaAgentException::CALL_TYPE_NOT_EXISTS . ": {$method}");
            }
            return $response;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @return string
     */
    public function getCookieFilePath()
    {
        return SzamlaAgentUtil::getBasePath() . DIRECTORY_SEPARATOR . $this->szamlazzClient->cookieFileName;
    }

    /**
     * Visszaadja az XML séma típusát
     * (számla, nyugta, adózó)
     *
     * @return string
     * @throws SzamlaAgentException
     */
    private function getXmlSchemaType()
    {
        switch ($this->getXmlName()) {
            case self::XML_SCHEMA_CREATE_INVOICE:
            case self::XML_SCHEMA_CREATE_REVERSE_INVOICE:
            case self::XML_SCHEMA_PAY_INVOICE:
            case self::XML_SCHEMA_REQUEST_INVOICE_PDF:
                $type = Document::DOCUMENT_TYPE_INVOICE;
                break;
            case self::XML_SCHEMA_DELETE_PROFORMA:
                $type = Document::DOCUMENT_TYPE_PROFORMA;
                break;
            case self::XML_SCHEMA_CREATE_RECEIPT:
            case self::XML_SCHEMA_CREATE_REVERSE_RECEIPT:
            case self::XML_SCHEMA_SEND_RECEIPT:
            case self::XML_SCHEMA_GET_RECEIPT:
                $type = Document::DOCUMENT_TYPE_RECEIPT;
                break;
            case self::XML_SCHEMA_TAXPAYER:
                $type = 'taxpayer';
                break;
            default:
                throw new SzamlaAgentException(
                    SzamlaAgentException::XML_SCHEMA_TYPE_NOT_EXISTS . ": {$this->getXmlName()}"
                );
        }
        return $type;
    }
}
