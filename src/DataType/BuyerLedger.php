<?php

declare(strict_types = 1);

namespace Cheppers\SzamlazzClient\DataType;

use DateTime;

class BuyerLedger extends Base
{
    /**
     * {@inheritdoc}
     */
    protected static $propertyMapping = [
        'bookingDate'         => 'konyvelesDatum',
        'buyerId'             => 'vevoAzonosito',
        'buyerLedgerNumber'   => 'vevoFokonyviSzam',
        'continuousCompletion' => 'folyamatosTelj',
    ];

    /**
     * @var DateTime
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
     * {@inheritdoc}
     */
    protected $complexTypeName = 'vevoFokonyv';

    /**
     * {@inheritdoc}
     */
    protected $requiredFields = [];
}
