<?php

declare(strict_types = 1);

namespace Cheppers\SzamlazzClient\DataType;

use DOMDocument;

class RequestBase
{
    /**
     * @var string
     */
    protected $fileName = '';

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
    protected $baseUrl = 'http://www.szamlazz.hu';

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

    protected function getXmlNs(string $xmlName): string
    {
        return "{$this->baseUrl}/{$xmlName}";
    }

    protected function getSchemaLocation(string $xmlName): string
    {
        return "{$this->baseUrl}/{$xmlName} {$this->baseUrl}/szamla/docs/xsds/$this->xsdDir/{$xmlName}.xsd";
    }

    protected function isEmpty(): bool
    {
        foreach ($this->requiredFields as $field) {
            if ($this->{$field} === null) {
                return true;
            }
        }

        return false;
    }
}
