<?php

declare(strict_types = 1);

namespace Cheppers\SzamlazzClient\DataType;

use Cheppers\SzamlazzClient\DataType\Buyer\InvoiceBuyer;
use Cheppers\SzamlazzClient\DataType\Header\InvoiceHeader;
use Cheppers\SzamlazzClient\DataType\Settings\InvoiceSettings;
use Cheppers\SzamlazzClient\DataType\Waybill\Waybill;
use Exception;

class GenerateInvoice extends RequestBase
{
    protected static $propertyMapping = [
        'settings'    => 'beallitasok',
        'header'      => 'fejlec',
        'seller'      => 'elado',
        'buyer'       => 'vevo',
        'waybill'     => 'fuvarlevel',
        'items'       => 'tetelek',
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
     * @var string
     */
    public $fileName = 'action-xmlagentxmlfile';

    /**
     * @var string
     */
    public $xsdDir = 'agent';

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

    /**
     * @throws Exception
     */
    public function buildXmlString(): string
    {
        $doc = $this->getXmlDocument();
        foreach (static::$propertyMapping as $internal => $external) {
            $value =  $this->{$internal};
            if (!in_array($internal, $this->requiredFields) && !$value) {
                continue;
            }

            if ($internal === 'items') {
                $items = $doc->createElement('tetelek');
                foreach ($this->items as $item) {
                    $itemElement = $doc->createElement('tetel');
                    $item->buildXmlData($itemElement);
                    $items->appendChild($itemElement);
                }
                $doc->documentElement->appendChild($items);

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
