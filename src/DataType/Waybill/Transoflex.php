<?php

declare(strict_types=1);

namespace Cheppers\SzamlazzClient\DataType\Waybill;

use Cheppers\SzamlazzClient\DataType\Base;

class Transoflex extends Base
{
    /**
     * {@inheritdoc}
     */
    protected static $propertyMapping = [
        'id'           => 'azonosito',
        'shippingId'   => 'shipmentID',
        'packetNumber' => 'csomagszam',
        'countryCode'  => 'countryCode',
        'zip'          => 'zip',
        'service'      => 'service',
    ];

    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $shippingId;

    /**
     * @var int
     */
    public $packetNumber;

    /**
     * @var string
     */
    public $countryCode;

    /**
     * @var string
     */
    public $zip;

    /**
     * @var string
     */
    public $service;

    /**
     * {@inheritdoc}
     */
    protected $complexTypeName = 'tof';
}
