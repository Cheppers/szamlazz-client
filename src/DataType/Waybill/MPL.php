<?php

declare(strict_types=1);

namespace Cheppers\SzamlazzClient\DataType\Waybill;

use Cheppers\SzamlazzClient\DataType\Base;

class MPL extends Base
{

    /**
     * {@inheritdoc}
     */
    protected static $propertyMapping = [
        'buyerCode'    => 'vevokod',
        'barcode'      => 'vonalkod',
        'weight'       => 'tomeg',
        'service'      => 'kulonszolgaltatasok',
        'insuredValue' => 'erteknyilvanitas',
    ];

    /**
     * {@inheritdoc}
     */
    protected $requiredFields = [
        'buyerCode',
        'barcode',
        'weight',
    ];

    /**
     * @var string
     */
    public $buyerCode;

    /**
     * @var string
     */
    public $barcode;

    /**
     * @var string
     */
    public $weight;

    /**
     * @var string
     */
    public $service;

    /**
     * @var double
     */
    public $insuredValue;
}
