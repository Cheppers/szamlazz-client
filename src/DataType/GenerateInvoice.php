<?php

declare(strict_types = 1);

namespace Cheppers\SzamlazzClient\DataType;

use Cheppers\SzamlazzClient\DataType\Buyer\InvoiceBuyer;
use Cheppers\SzamlazzClient\DataType\Header\InvoiceHeader;
use Cheppers\SzamlazzClient\DataType\Settings\InvoiceSettings;
use Cheppers\SzamlazzClient\DataType\Waybill\Waybill;
use Exception;
use phpDocumentor\Reflection\Types\This;

class GenerateInvoice extends RequestBase
{
    /**
     * @var string
     */
    public $fileName = 'action-xmlagentxmlfile';

    /**
     * @var string
     */
    public $xsdDir = 'agent';

    /**
     * @var string
     */
    protected $xmlName = 'xmlszamla';

    /**
     * {@inheritdoc}
     */
    protected $requiredFields = [
        'settings',
        'header',
        'seller',
        'buyer',
        'items',
    ];
    protected static $propertyMapping = [
        'settings'    => 'beallitasok',
        'header'      => 'fejlec',
        'seller'      => 'elado',
        'buyer'       => 'vevo',
        'waybill'     => 'fuvarlevel',
        'items'       => 'tetelek',
    ];

    /**
     * @var InvoiceSettings
     */
    public $settings;

    /**
     * @var InvoiceHeader
     */
    public $header;

    /**
     * @var InvoiceBuyer
     */
    public $buyer;

    /**
     * @var Seller
     */
    public $seller;

    /**
     * @var Waybill
     */
    public $waybill;

    /**
     * @var Item[]
     */
    public $items = [];

    public static function __set_state($values)
    {
        $instance = new static();

        foreach ($values as $key => $value) {
            if (!property_exists($instance, $key)) {
                continue;
            }

            switch ($key) {
                case 'settings':
                    $instance->settings = InvoiceSettings::__set_state($value);
                    break;
                case 'header':
                    $instance->header = InvoiceHeader::__set_state($value);
                    break;
                case 'buyer':
                    $instance->buyer = InvoiceBuyer::__set_state($value);
                    break;
                case 'seller':
                    $instance->seller = Seller::__set_state($value);
                    break;
                case 'waybill':
                    $instance->waybill = Waybill::__set_state($value);
                    break;
                case 'items':
                    foreach ($value as $item) {
                        $instance->items[] = Item::__set_state($item);
                    }
                    break;
            }
        }

        return $instance;
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
            $value =  $this->{$internal};
            if (!in_array($internal, $this->requiredFields) && !$value) {
                continue;
            }

            if ($internal === 'items') {
                $items = $doc->createElement('tetelek');
                foreach ($this->items as $item) {
                    $doc = $item->buildXmlData($doc);
                    $itemElement = $doc->getElementsByTagName('tetel')->item(0);
                    $items->appendChild($itemElement);
                }
                $doc->documentElement->appendChild($items);
                continue;
            }

            $doc = $this->{$internal}->buildXmlData($doc);
        }

        return $doc->saveXML();
    }
}
