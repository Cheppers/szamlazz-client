<?php

declare(strict_types=1);

namespace Cheppers\SzamlazzClient\DataType;

use Cheppers\SzamlazzClient\DataType\CreditNote\ReceiptCreditNote;
use Cheppers\SzamlazzClient\DataType\Header\ReceiptHeader;

class GenerateReceipt extends Base
{
    /**
     * {@inheritdoc}
     */
    protected $complexTypeName = 'xmlnyugtacreate';

    /**
     * {@inheritdoc}
     */
    protected static $propertyMapping = [
        'settings' => 'beallitasok',
        'header' => 'fejlec',
        'items' => 'tetelek',
        'creditNotes' => 'kifizetesek',
    ];

    /**
     * @var Settings
     */
    public $settings;

    /**
     * @var ReceiptHeader
     */
    public $header;

    /**
     * @var Item[]
     */
    public $items;

    /**
     * @var ReceiptCreditNote[]
     */
    public $creditNotes;
}
