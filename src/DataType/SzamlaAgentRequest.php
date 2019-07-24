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

    const SESSION_CONNECTION_TYPE = 'connectionType';

    const REQUEST_TIMEOUT = 30;

    const XML_SCHEMA_CREATE_INVOICE = 'xmlszamla';

    const XML_SCHEMA_CREATE_REVERSE_INVOICE = 'xmlszamlast';

    const XML_SCHEMA_PAY_INVOICE = 'xmlszamlakifiz';

    const XML_SCHEMA_REQUEST_INVOICE_PDF = 'xmlszamlapdf';

    const XML_SCHEMA_REQUEST_INVOICE_XML = 'xmlszamlaxml';

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
     * @var string;
     */
    public $xmlFileName = '';

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

        $fullXmlData = $this->entity->buildXmlData($this);

        $xml = new \SimpleXMLElement($this->getXmlBase());
        $this->arrayToXML($fullXmlData, $xml);

        $result = SzamlaAgentUtil::checkValidXml($xml);
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
                $xmlFields->addChild($key, $value);
            }
            if (is_array($value)) {
                $xmlArray = $xmlFields->addChild($key);
                foreach ($value as $ik => $iv) {
                    $xmlArray->addChild($ik, $iv);
                }
            }
        }

        return;
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

        $this->xmlFileName = $fileName;
    }

    protected function getXmlBase(): string
    {
        $xmlName = $this->xmlName;

        $queryData = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL
            . '<' . $xmlName . ' xmlns="' . $this->getXmlNs($xmlName) . '"'
            . ' xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"'
            . ' xsi:schemaLocation="' . $this->getSchemaLocation($xmlName) . '">' . PHP_EOL
            . '</'.$xmlName.'>' . PHP_EOL;

        return $queryData;
    }

    protected function getSchemaLocation(string $xmlName): string
    {
        return "http://www.szamlazz.hu/{$xmlName} http://www.szamlazz.hu/docs/xsds/$this->xsdDir/{$xmlName}.xsd";
    }

    public function getXmlNs(string $xmlName): string
    {
        return "http://www.szamlazz.hu/{$xmlName}";
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
            case 'requestInvoiceXML':
                $fileName =  'action-szamla_agent_xml';
                $xmlName  = self::XML_SCHEMA_REQUEST_INVOICE_XML;
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
     * @return string
     */
    public function getCookieFilePath()
    {
        return SzamlaAgentUtil::getBasePath() . DIRECTORY_SEPARATOR . $this->szamlazzClient->cookieFileName;
    }
}
