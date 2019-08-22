<?php

declare(strict_types = 1);

namespace Cheppers\SzamlazzClient\DataType;

use DOMDocument;

abstract class RequestBase
{
    /**
     * @var string
     */
    public $fileName = '';

    /**
     * @var string[]
     */
    protected $requiredFields = [];

    /**
     * @var string
     */
    protected $xsdDir = '';

    /**
     * @var string
     */
    protected $xmlName = '';

    /**
     * @var string
     */
    protected $xmlNsBaseUrl = 'http://www.szamlazz.hu';

    public function getXmlNsBaseUrl(): string
    {
        return  $this->xmlNsBaseUrl;
    }

    public function setXmlNsBaseUrl(string $url)
    {
        $this->xmlNsBaseUrl = $url;

        return $this;
    }

    /**
     * @var bool
     */
    protected $formatOutput = false;

    public function getFormatOutput(): bool
    {
        return $this->formatOutput;
    }

    /**
     * @return $this
     */
    public function setFormatOutput(bool $formatOutput)
    {
        $this->formatOutput = $formatOutput;

        return $this;
    }

    abstract public function buildXmlString(): string;

    public function getXmlDocument(): DOMDocument
    {
        $xmlName = $this->xmlName;
        $doc = new DOMDocument('1.0', 'UTF-8');
        $doc->formatOutput = $this->getFormatOutput();
        $root = $doc->createElementNS($this->getXmlNs($xmlName), $xmlName);
        $doc->appendChild($root);
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

    protected function getXmlNs(string $xmlName): string
    {
        return $this->getXmlNsBaseUrl() . "/$xmlName";
    }

    protected function getSchemaLocation(string $xmlName): string
    {
        $baseUrl = $this->getXmlNsBaseUrl();

        return "$baseUrl/$xmlName $baseUrl/szamla/docs/xsds/{$this->xsdDir}/$xmlName.xsd";
    }

    public function isEmpty(): bool
    {
        foreach ($this->requiredFields as $field) {
            if ($this->{$field} === null) {
                return true;
            }
        }

        return false;
    }
}
