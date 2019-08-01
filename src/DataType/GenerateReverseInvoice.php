<?php

declare(strict_types = 1);

namespace Cheppers\SzamlazzClient\DataType;

use Cheppers\SzamlazzClient\DataType\Header\InvoiceHeader;
use Cheppers\SzamlazzClient\DataType\Header\ReverseInvoiceHeader;

class GenerateReverseInvoice extends RequestBase
{
    /**
     * @var string
     */
    public $fileName = 'action-szamla_agent_st';

    /**
     * @var string
     */
    protected $xsdDir = 'agentst';

    /**
     * @var string
     */
    protected $xmlName = 'xmlszamlast';

    /**
     * @var string[]
     */
    protected $requiredFields = [
        'settings',
        'header',
        'seller',
        'buyer',
    ];

    protected static $propertyMapping = [
        'settings'    => 'beallitasok',
        'header'      => 'fejlec',
        'seller'      => 'elado',
        'buyer'       => 'vevo',
    ];

    /**
     * @var \Cheppers\SzamlazzClient\DataType\Settings
     */
    public $settings;

    /**
     * @var \Cheppers\SzamlazzClient\DataType\Header\ReverseInvoiceHeader
     */
    public $header;

    /**
     * @var \Cheppers\SzamlazzClient\DataType\Seller
     */
    public $seller;

    /**
     * @var \Cheppers\SzamlazzClient\DataType\Buyer
     */
    public $buyer;

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
                case 'header':
                    $instance->header = ReverseInvoiceHeader::__set_state($value);
                    break;
                case 'buyer':
                    $instance->buyer = Buyer::__set_state($value);
                    break;
                case 'seller':
                    $instance->seller = Seller::__set_state($value);
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

            $doc = $this->{$internal}->buildXmlData($doc);
        }

        return $doc->saveXML();
    }
}
