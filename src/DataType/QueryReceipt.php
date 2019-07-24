<?php

declare(strict_types = 1);

namespace Cheppers\SzamlazzClient\DataType;

class QueryReceipt extends Base
{

    /**
     * @var bool
     */
    public $downloadPdf;

    /**
     * @var string
     */
    public $receiptId;

    /**
     * @var string
     */
    public $pdfScheme;

    /**
     * @var string[]
     */
    public $settings;

    /**
     * @var string[]
     */
    public $header;

    /**
     * {@inheritdoc}
     */
    protected static $propertyMapping = [
        'downloadPdf' => 'pdfLetoltes',
        'receiptId' => 'nyugtaszam',
        'pdfScheme' => 'pdfSablon',
        'settings' => 'beallitasok',
        'header' => 'fejlec',
    ];
}
