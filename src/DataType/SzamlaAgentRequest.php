<?php

declare(strict_types = 1);

namespace Cheppers\SzamlazzClient\DataType;

use Cheppers\SzamlazzClient\SzamlazzClientException;
use DOMDocument;

class SzamlaAgentRequest
{

    const XML_SCHEMA_CREATE_INVOICE = 'xmlszamla';

    const XML_SCHEMA_CREATE_REVERSE_INVOICE = 'xmlszamlast';

    const XML_SCHEMA_TAXPAYER = 'xmltaxpayer';

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
    protected $baseUrl = 'https://www.szamlazz.hu';

    /**
     * @throws \Cheppers\SzamlazzClient\SzamlazzClientException
     */
    public function setFields(string $type, array $data)
    {
        $this->type = $type;
        switch ($this->type) {
            case 'generateInvoice':
                $this->fileName = 'action-xmlagentxmlfile';
                $this->xmlName = static::XML_SCHEMA_CREATE_INVOICE;
                $this->xsdDir = 'agent';
                $this->entity = Invoice::__set_state($data);
                break;

            case 'generateReverseInvoice':
                $this->fileName = 'action-szamla_agent_st';
                $this->xmlName = static::XML_SCHEMA_CREATE_REVERSE_INVOICE;
                $this->xsdDir = 'agentst';
                break;

            case 'getTaxPayer':
                $this->fileName = 'action-szamla_agent_taxpayer';
                $this->xmlName = static::XML_SCHEMA_TAXPAYER;
                $this->xsdDir = 'taxpayer';
                $this->entity = TaxPayer::__set_state($data);
                break;

            default:
                // @todo Consider to use assert() instead.
                throw new SzamlazzClientException(SzamlazzClientException::REQUEST_TYPE_NOT_EXISTS . ": {$type}");
        }
    }

    /**
     * @throws \Cheppers\SzamlazzClient\SzamlazzClientException
     * @throws \ReflectionException
     */
    public function buildXml(): string
    {
        $docBase = $this->getXmlBase();
        /** @var \DOMDocument $doc */
        $doc = $this->entity->buildXmlData($docBase);
        $doc->formatOutput = true;

        return $doc->saveXML();
    }

    public function getXmlBase(): DOMDocument
    {
        $xmlName = $this->xmlName;
        $doc = new DOMDocument('1.0', 'UTF-8');
        /** @var \DOMElement $root */
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

    public function getXmlNs(string $xmlName): string
    {
        return "{$this->baseUrl}/{$xmlName}";
    }

    protected function getSchemaLocation(string $xmlName): string
    {
        return "{$this->baseUrl}/{$xmlName} {$this->baseUrl}/szamla/docs/xsds/$this->xsdDir/{$xmlName}.xsd";
    }
}
