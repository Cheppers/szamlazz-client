<?php

declare(strict_types=1);

namespace Cheppers\SzamlazzClient\DataType;

class ItemLedger extends Base
{

    /**
     * {@inheritdoc}
     */
    protected static $propertyMapping = [
        'economicEvent'       => 'gazdasagiEsem',
        'economicEventVat'    => 'gazdasagiEsemAfa',
        'revenueLedgerNumber' => 'arbevetelFokonyviSzam',
        'vatLedgerNumber'     => 'afaFokonyviSzam',
        'payOffDateFrom'      => 'elszDatumTol',
        'payOffDateTo'        => 'elszDatumIg',
    ];

    /**
     * @var string
     */
    public $economicEvent;

    /**
     * @var string
     */
    public $economicEventVat;

    /**
     * @var string
     */
    public $revenueLedgerNumber;

    /**
     * @var string
     */
    public $vatLedgerNumber;

    /**
     * @var \DateTime
     */
    public $payOffDateFrom;

    /**
     * @var \DateTime
     */
    public $payOffDateTo;
}
