<?php

declare(strict_types=1);

namespace Cheppers\SzamlazzClient\DataType;

class BuyerLedger extends Base
{

    /**
     * {@inheritdoc}
     */
    protected $complexTypeName = 'vevoFokonyv';

    /**
     * @return mixed
     */
    public function getComplexTypeName()
    {
        return $this->complexTypeName;
    }

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
     * {@inheritdoc}
     */
    protected $requiredFields = [];
}
