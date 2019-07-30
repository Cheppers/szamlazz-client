<?php

declare(strict_types=1);

namespace Cheppers\SzamlazzClient\DataType;

class BuyerLedger extends Base
{

    /**
     * {@inheritdoc}
     */
    protected $complexTypeName = 'vevoFokonyvTipus';

    /**
     * {@inheritdoc}
     */
    protected static $propertyMapping = [
        'bookingDate'         => 'konyvelesDatum',
        'buyerId'             => 'vevoAzonosito',
        'buyerLedgerNumber'   => 'vevoFokonyviSzam',
        'continousCompletion' => 'folyamatosTelj',
    ];

    /**
     * @var \DateTime
     */
    public $bookingDate;

    /**
     * @var string
     */
    public $buyerId;

    /**
     * @var string
     */
    public $buyerLedgerNumber;

    /**
     * @var bool
     */
    public $continuousCompletion;

    /**
     * @var boolean
     */
    public $continuedFulfillment = false;

    public function isEmpty(): bool
    {
        return $this->buyerId === null;
    }
}
