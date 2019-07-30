<?php

declare(strict_types=1);

namespace Cheppers\SzamlazzClient\DataType\Waybill;

use Cheppers\SzamlazzClient\DataType\Base;

class PPP
{

    /**
     * {@inheritdoc}
     */
    protected static $propertyMapping = [
        'barcodePrefix'  => 'vonalkodPrefix',
        'barcodePostfix' => 'vonalkodPostfix',
    ];

    /**
     * @var string
     */
    public $barcodePrefix;

    /**
     * @var string
     */
    public $barcodePostfix;
}
