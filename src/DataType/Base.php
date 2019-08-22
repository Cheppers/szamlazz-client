<?php

declare(strict_types = 1);

namespace Cheppers\SzamlazzClient\DataType;

abstract class Base
{
    /**
     * @var string[]
     */
    protected static $propertyMapping = [];

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

    /**
     * @var string
     */
    protected $complexTypeName;

    /**
     * Internal name of the required fields.
     *
     * @var string[]
     */
    protected $requiredFields = [];

    public function getComplexTypeName(): string
    {
        return $this->complexTypeName;
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

    /**
     * @return $this
     */
    public function buildXmlData(\DOMElement $element)
    {
        $doc = $element->ownerDocument;
        foreach (static::$propertyMapping as $internal => $external) {
            $value =  $this->{$internal};

            if (!in_array($internal, $this->requiredFields) && $value === null) {
                continue;
            }

            if (is_bool($value)) {
                $value = $value ? 'true' : 'false';
            }

            if ($value instanceof Base) {
                $subElement = $doc->createElement($value->getComplexTypeName());
                $value->buildXmlData($subElement);
                $element->appendChild($subElement);

                continue;
            }

            $newItem = $doc->createElement($external);
            $newItem->nodeValue = $value;
            $element->appendChild($newItem);
        }

        return $this;
    }
}
