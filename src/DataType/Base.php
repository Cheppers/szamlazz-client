<?php

declare(strict_types=1);

namespace Cheppers\SzamlazzClient\DataType;

abstract class Base
{

    /**
     * @var string
     */
    protected $complexTypeName;

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

    abstract public function isEmpty(): bool;

    public function buildXmlData(\DOMDocument $doc): \DOMDocument
    {
        if ($this->isEmpty()) {
            return $doc;
        }

        $element = $doc->createElement($this->complexTypeName);

        foreach (static::$propertyMapping as $internal => $external) {
            $value =  $this->{$internal};
            if (!in_array($internal, $this->requiredFields) && !$value) {
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
