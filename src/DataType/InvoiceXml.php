<?php

declare(strict_types=1);

namespace Cheppers\SzamlazzClient\DataType;

class InvoiceXml extends Base {

    /**
     * @var string
     */
    public $invoiceId;

    /**
     * @var string
     */
    public $orderId;

    /**
     * @var bool
     */
    public $pdf;

    /**
     * {@inheritdoc}
     */
    protected $parents = false;

    /**
     * {@inheritdoc}
     */
    protected static $propertyMapping = [
        'invoiceId' => 'szamlaszam',
        'orderId' => 'rendelesszam',
        'pdf' => 'pdf',
    ];
}
