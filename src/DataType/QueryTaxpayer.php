<?php

declare(strict_types = 1);

namespace Cheppers\SzamlazzClient\DataType;

use Cheppers\SzamlazzClient\DataType\Settings\SettingsBase;
use Exception;

class QueryTaxpayer extends RequestBase
{
    /**
     * @var string[]
     */
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
                    $instance->settings = SettingsBase::__set_state($value);
                    break;

                case 'taxpayerId':
                    $instance->taxpayerId = $value;
                    break;
            }
        }

        return $instance;
    }

    /**
     * @var string
     */
    public $fileName = 'action-szamla_agent_taxpayer';

    /**
     * @var string
     */
    public $xsdDir = 'taxpayer';

    /**
     * @var string
     */
    public $xmlName = 'xmltaxpayer';

    /**
     * @var SettingsBase
     */
    public $settings;

    /**
     * @var int
     */
    public $taxpayerId;

    /**
     * @var string[]
     */
    protected $requiredFields = [
        'settings',
        'taxpayerId',
    ];

    public function __construct()
    {
        $this->settings = new SettingsBase();
    }

    public function buildXmlString(): string
    {
        $doc = $this->getXmlDocument();

        foreach (static::$propertyMapping as $internal => $external) {
            $value = $this->{$internal};
            if ($internal === 'taxpayerId') {
                $element = $doc->createElement('torzsszam', (string) $this->taxpayerId);
                $doc->documentElement->appendChild($element);

                continue;
            }

            /** @var \Cheppers\SzamlazzClient\DataType\Base $value */
            $subElement = $doc->createElement($value->getComplexTypeName());
            $doc->documentElement->appendChild($subElement);
            $value->buildXmlData($subElement);
        }

        return $doc->saveXML();
    }
}
