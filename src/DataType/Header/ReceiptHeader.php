<?php

declare(strict_types=1);

namespace Cheppers\SzamlazzClient\DataType\Header;

use Cheppers\SzamlazzClient\DataType\Base;

class ReceiptHeader
{

    /**
     * {@inheritdoc}
     */
    protected $complexTypeName = 'fejlec';

    /**
     * {@inheritdoc}
     */
    protected static $propertyMapping = [
        'callId'        => 'hivasAzonosito',
        'prefix'        => 'elotag',
        'paymentMethod' => 'fizmod',
        'currency'      => 'penznem',
        'exchangeRate'  => 'devizaarf',
        'exchangeBank'  => 'devizabank',
        'comment'       => 'megjegyzes',
        'pdfTemplate'   => 'pdfSablon',
        'buyerLedgerId' => 'fokonyvVevo',
    ];

    /**
     * {@inheritdoc}
     */
    protected $requiredFields = [
        'prefix',
        'paymentMethod',
        'currency',
    ];

    /**
     * @var string
     */
    public $callId;

    /**
     * @var string
     */
    public $prefix;

    /**
     * @var string
     */
    public $paymentMethod;

    /**
     * @var string
     */
    public $currency;

    /**
     * @var double
     */
    public $exchangeRate;

    /**
     * @var string
     */
    public $exchangeBank;

    /**
     * @var string
     */
    public $comment;

    /**
     * @var string
     */
    public $pdfTemplate;

    /**
     * @var string
     */
    public $buyerLedgerId;
}
