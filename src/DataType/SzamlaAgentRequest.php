<?php

declare(strict_types=1);

namespace Cheppers\SzamlazzClient\DataType;

use Cheppers\SzamlazzClient\SzamlazzClient;
use Cheppers\SzamlazzClient\SzamlazzClientException;
use Cheppers\SzamlazzClient\Utils\SzamlaAgentUtil;

class SzamlaAgentRequest
{
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

    public $xmlFileName;

    /**
     * @var string
     */
    public $fileName;

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
    public function buildXmlData()
    {
        $this->setXmlFileData($this->type);

        $fullXmlData['beallitasok'] = [
            'szamlaagentkulcs' => $this->szamlazzClient->getApiKey(),
        ];

        $fullXmlData += $this->entity->buildXmlData($this);

        $doc = $this->getXmlBase();
        $xml = $this->arrayToXML($fullXmlData, $doc);
        $xml->formatOutput = true;

        return $xml->saveXML();
    }

    protected function arrayToXML(array $xmlData, \DOMDocument &$doc)
    {
        $x = $doc->getElementsByTagName($this->xmlName)->item(0);
        foreach ($xmlData as $key => $value) {
            if (!is_array($value)) {
                $child = $doc->createElement($key, $value);
                $x->appendChild($child);
            }
            if (is_array($value)) {
                $child = $doc->createElement($key);
                foreach ($value as $ik => $iv) {
                    $grandChild = $doc->createElement($ik, $iv);
                    $child->appendChild($grandChild);
                    $x->appendChild($child);
                }
            }
        }

        return $doc;
    }

    /**
     * @return \DOMDocument
     */
    protected function getXmlBase()
    {
        $xmlName = $this->xmlName;
        $doc = new \DOMDocument('1.0', 'UTF-8');
        $root = $doc->createElementNS($this->getXmlNs($xmlName), $xmlName);
        $root = $doc->appendChild($root);
        $root->setAttributeNS(
            'http://www.w3.org/2000/xmlns/',
            'xmlns:xsi',
            'http://www.w3.org/2001/XMLSchema-instance'
        );
        $root->setAttributeNS(
            'http://www.w3.org/2001/XMLSchema-instance',
            'schemaLocation',
            $this->getSchemaLocation($xmlName)
        );

        return $doc;
    }

    protected function getSchemaLocation(string $xmlName): string
    {
        return "http://www.szamlazz.hu/{$xmlName} http://www.szamlazz.hu/docs/xsds/$this->xsdDir/{$xmlName}.xsd";
    }

    public function getXmlNs(string $xmlName): string
    {
        return "http://www.szamlazz.hu/{$xmlName}";
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
                $xsdDir   = 'agent';
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
}
