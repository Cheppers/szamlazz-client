<?php

declare(strict_types=1);

namespace Cheppers\SzamlazzClient\DataType\Waybill;

use Cheppers\SzamlazzClient\DataType\Base;

class Sprinter extends Base
{
    /**
     * {@inheritdoc}
     */
    protected static $propertyMapping = [
        'id'             => 'azonosito',
        'senderId'       => 'feladokod',
        'shipmentZip'    => 'iranykod',
        'packetNumber'   => 'csomagszam',
        'barcodePostfix' => 'vonalkodPostfix',
        'shippingTime'   => 'szallitasiIdo',
    ];

    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $senderId;

    /**
     * @var string
     */
    public $shipmentZip;

    /**
     * @var int
     */
    public $packetNumber;

    /**
     * @var string
     */
    public $barcodePostfix;

    /**
     * @var string
     */
    public $shippingTime;

    public function isEmpty(): bool
    {
        return $this->id === null;
    }
}
