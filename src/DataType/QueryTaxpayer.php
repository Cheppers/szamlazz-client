<?php

declare(strict_types = 1);

namespace Cheppers\SzamlazzClient\DataType;

use Cheppers\SzamlazzClient\DataType\Settings\SettingsBase;
use Exception;

/**
 * @covers \Cheppers\SzamlazzClient\DataType\QueryTaxpayer<extended>
 */
class QueryTaxpayer extends RequestBase
{
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

    public function __construct()
    {
        $this->settings = new SettingsBase();
    }

    /**
     * @throws Exception
     */
    public function buildXmlString(): string
    {
        if ($this->isEmpty()) {
            throw new Exception('Missing required field');
        }

        $doc = $this->getXmlBase();

        foreach (static::$propertyMapping as $internal => $external) {
            if ($internal === 'taxpayerId') {
                $element = $doc->createElement('torzsszam', (string) $this->taxpayerId);
                $doc->documentElement->appendChild($element);

                continue;
            }

            $doc = $this->{$internal}->buildXmlData($doc);
        }

        return $doc->saveXML();
    }
}
