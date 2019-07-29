<?php

declare(strict_types=1);

namespace Cheppers\SzamlazzClient\DataType\Waybill;

use Cheppers\SzamlazzClient\DataType\Base;

class Waybill extends Base
{

    /**
     * {@inheritdoc}
     */
    protected $complexTypeName = 'fuvarlevel';

    /**
     * {@inheritdoc}
     */
    protected static $propertyMapping = [
        'destination' => 'uticel',
        'parcel'      => 'futarSzolgalat',
        'barcode'     => 'vonalkod',
        'comment'     => 'megjegyzes',
        'tof'         => 'tof',
        'ppp'         => 'ppp',
        'sprinter'    => 'sprinter',
        'mpl'         => 'mpl',
    ];

    /**
     * @var string
     */
    public $destination;

    /**
     * @var string
     */
    public $parcel;

    /**
     * @var string
     */
    public $barcode;

    /**
     * @var string
     */
    public $comment;

    /**
     * @var Transoflex
     */
    public $tof;

    /**
     * @var PPP
     */
    public $ppp;

    /**
     * @var Sprinter
     */
    public $sprinter;

    /**
     * @var MPL
     */
    public $mpl;
}
