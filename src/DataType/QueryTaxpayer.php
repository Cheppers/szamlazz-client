<?php

declare(strict_types=1);

namespace Cheppers\SzamlazzClient\DataType;

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
    protected $requiredFields = ['settings', 'taxpayerId'];

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

    /**
     * @throws \Exception
     */
    public function buildXmlString(): string
    {
        if ($this->isEmpty()) {
            throw new \Exception('Missing required field');
        }

        $doc = $this->getXmlBase();

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

        return $doc->saveXML();
    }
}
