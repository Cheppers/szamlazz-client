<?php

declare(strict_types = 1);

namespace Cheppers\SzamlazzClient\DataType;

use Cheppers\SzamlazzClient\DataType\Buyer\BuyerBase;
use Cheppers\SzamlazzClient\DataType\Header\ReverseInvoiceHeader;
use Cheppers\SzamlazzClient\DataType\Settings\ReverseInvoiceSettings;
use Exception;

class GenerateReverseInvoice extends RequestBase
{
    protected static $propertyMapping = [
        'settings'    => 'beallitasok',
        'header'      => 'fejlec',
        'seller'      => 'elado',
        'buyer'       => 'vevo',
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
                    $instance->settings = ReverseInvoiceSettings::__set_state($value);
                    break;
                case 'header':
                    $instance->header = ReverseInvoiceHeader::__set_state($value);
                    break;
                case 'buyer':
                    $instance->buyer = BuyerBase::__set_state($value);
                    break;
                case 'seller':
                    $instance->seller = Seller::__set_state($value);
                    break;
            }
        }

        return $instance;
    }

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

    /**
     * @var ReverseInvoiceSettings
     */
    public $settings;

    /**
     * @var ReverseInvoiceHeader
     */
    public $header;

    /**
     * @var Seller
     */
    public $seller;

    /**
     * @var BuyerBase
     */
    public $buyer;

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
            $value =  $this->{$internal};
            if (!in_array($internal, $this->requiredFields) && !$value) {
                continue;
            }

            $doc = $this->{$internal}->buildXmlData($doc);
        }

        return $doc->saveXML();
    }
}
