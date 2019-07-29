<?php

declare(strict_types=1);

namespace Cheppers\SzamlazzClient\DataType;

use Cheppers\SzamlazzClient\DataType\Header\InvoiceHeader;
use Cheppers\SzamlazzClient\DataType\Waybill\Waybill;
use Cheppers\SzamlazzClient\SzamlazzClientException;

class Invoice extends Base
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
        'buyer'       => 'vevo',
        'buyerLedger' => 'vevoFokonyv',
        'seller'      => 'elado',
        'waybill'     => 'fuvarlevel',
        'items'       => 'tetelek',
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
                    $instance->items = Item::__set_state($value);
                    break;
            }
        }

        return $instance;
    }
}
