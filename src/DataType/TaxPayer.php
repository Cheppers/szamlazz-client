<?php

declare(strict_types=1);

namespace Cheppers\SzamlazzClient\DataType;

class TaxPayer
{
    /**
     * @var \Cheppers\SzamlazzClient\DataType\Settings
     */
    public $settings;

    /**
     * @var int
     */
    public $taxpayerId;

    /**
     * @var string[]
     */
    public $requiredFields = ['settings', 'taxPayerId'];

    protected static $propertyMapping = [
        'settings'   => 'beallitasok',
        'taxpayerId' => 'torzsszam',
    ];

    public static function __set_state($values)
    {
        $instance = new static();

        foreach ($values as $key => $value) {
            if (!property_exists($instance, $key)) {
                continue;
            }

            switch ($key) {
                case 'settings':
                    $instance->settings = Settings::__set_state($value);
                    break;
                case 'taxpayerId':
                    $instance->taxpayerId = $value;
                    break;
            }
        }

        return $instance;
    }

    public function isEmpty(): bool
    {
        foreach ($this->requiredFields as $field) {
            if ($field === null) {
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

        foreach (static::$propertyMapping as $internal => $external) {
            $value =  $this->{$internal};
            if (!in_array($internal, $this->requiredFields) && !$value) {
                continue;
            }

            if ($internal === 'taxpayerId') {
                $element = $doc->createElement('torzsszam', (string) $this->taxpayerId);
                $doc->documentElement->appendChild($element);
                continue;
            }

            $doc = $this->{$internal}->buildXmlData($doc);
        }

        return $doc;
    }
}
