<?php

declare(strict_types=1);

namespace Cheppers\SzamlazzClient\DataType;

use Cheppers\SzamlazzClient\DataType\Header\InvoiceHeader;
use Cheppers\SzamlazzClient\DataType\Waybill\Waybill;
use Cheppers\SzamlazzClient\SzamlazzClientException;
use DoctrineTest\InstantiatorTestAsset\AbstractClassAsset;

class Invoice
{
    /**
     * {@inheritdoc}
     */
    protected $complexTypeName = 'xmlszamla';

    /**
     * {@inheritdoc}
     */
    protected static $propertyMapping = [
        'settings'    => 'beallitasok',
        'header'      => 'fejlec',
        'seller'      => 'elado',
        'buyer'       => 'vevo',
        'waybill'     => 'fuvarlevel',
        'items'       => 'tetelek',
        'buyerLedger' => 'vevoFokonyv',
    ];

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
     * @var \Cheppers\SzamlazzClient\DataType\Settings
     */
    public $settings;

    /**
     * @var \Cheppers\SzamlazzClient\DataType\Header\InvoiceHeader
     */
    public $header;

    /**
     * @var \Cheppers\SzamlazzClient\DataType\Buyer
     */
    public $buyer;

    /**
     * @var \Cheppers\SzamlazzClient\DataType\BuyerLedger
     */
    public $buyerLedger;

    /**
     * @var \Cheppers\SzamlazzClient\DataType\Seller
     */
    public $seller;

    /**
     * @var \Cheppers\SzamlazzClient\DataType\Waybill\Waybill
     */
    public $waybill;

    /**
     * @var \Cheppers\SzamlazzClient\DataType\Item[]
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
                    $instance->settings = Settings::__set_state($value);
                    break;
                case 'header':
                    $instance->header = InvoiceHeader::__set_state($value);
                    break;
                case 'buyer':
                    $instance->buyer = Buyer::__set_state($value);
                    break;
                case 'buyerLedger':
                    $instance->buyerLedger = BuyerLedger::__set_state($value);
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

    public function isEmpty(): bool
    {
        return $this->settings === null;
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

            if ($internal === 'items') {
                foreach ($this->items as $item) {
                    $doc = $item->buildXmlData($doc);
                }
                $itemElements = $doc->getElementsByTagName('tetel');
                $items = $doc->createElement('tetelek');
                foreach ($itemElements as $itemElement) {
                    $items->appendChild($itemElement);
                }
                $doc->documentElement->appendChild($items);
                continue;
            }

            $doc = $this->{$internal}->buildXmlData($doc);
        }

        return $doc;
    }
}
