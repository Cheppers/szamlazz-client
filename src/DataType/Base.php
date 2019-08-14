<?php

declare(strict_types=1);

namespace Cheppers\SzamlazzClient\DataType;

abstract class Base
{

    /**
     * @var string
     */
    protected $complexTypeName;

    public function getComplexTypeName(): string
    {
        return $this->complexTypeName;
    }

    /**
     * @var string[]
     */
    protected static $propertyMapping = [];

    /**
     * Internal name of the required fields.
     *
     * @var string[]
     */
    protected $requiredFields = [];

    public static function __set_state($values)
    {
        $instance = new static();

        foreach ($values as $key => $value) {
            if (!property_exists($instance, $key)) {
                continue;
            }

            $instance->{$key} = $value;
        }

        return $instance;
    }

    public function isEmpty(): bool
    {
        foreach ($this->requiredFields as $field) {
            if (!isset($this->{$field})) {
                return true;
            }
        }

        return false;
    }

    public function buildXmlData(\DOMDocument $doc): \DOMDocument
    {
        if ($this->isEmpty()) {
            return $doc;
        }

        $element = $doc->createElement($this->complexTypeName);

        foreach (static::$propertyMapping as $internal => $external) {
            $value =  $this->{$internal};

            if (!in_array($internal, $this->requiredFields) && !isset($value)) {
                continue;
            }

            if (is_bool($value)) {
                $value = $value ? 'true' : 'false';
            }

            if (is_object($value)) {
                $value->buildXmlData($doc);
                $subElement = $doc->getElementsByTagName($value->getComplexTypeName())->item(0);
                $element->appendChild($subElement);
                continue;
            }

            $newItem = new \DOMElement($external);

            $newItem->nodeValue = $value;
            $element->appendChild($newItem);
        }
        $doc->documentElement->appendChild($element);

        return $doc;
    }
}
