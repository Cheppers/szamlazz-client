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

    protected $requiredFields = [];

    public static function __set_state($values)
    {
        $instance = new static();

        foreach ($values as $key => $value) {
            if (!property_exists($instance, $key)) {
                continue;
            }

            switch ($key) {
                case 'destination':
                    $instance->destination = $value;
                    break;

                case 'parcel':
                    $instance->parcel = $value;
                    break;

                case 'comment':
                    $instance->comment = $value;
                    break;

                case 'barcode':
                    $instance->barcode = $value;
                    break;

                case 'tof':
                    $instance->tof = Transoflex::__set_state($value);
                    break;

                case 'ppp':
                    $instance->ppp = PPP::__set_state($value);
                    break;

                case 'sprinter':
                    $instance->sprinter = Sprinter::__set_state($value);
                    break;

                case 'mpl':
                    $instance->mpl = MPL::__set_state($value);
                    break;
            }
        }

        return $instance;
    }
}
