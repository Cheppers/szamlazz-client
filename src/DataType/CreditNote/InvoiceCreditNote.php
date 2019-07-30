<?php

declare(strict_types=1);

namespace Cheppers\SzamlazzClient\DataType\CreditNote;

use Cheppers\SzamlazzClient\DataType\Base;

class InvoiceCreditNote
{
    /**
     * {@inheritdoc}
     */
    protected $complexTypeName = 'kifizetes';

    /**
     * {@inheritdoc}
     */
    protected static $propertyMapping = [
        'date'        => 'datum',
        'paymentMode' => 'jogcim',
        'amount'      => 'osszeg',
        'description' => 'leiras',
    ];

    /**
     * {@inheritdoc}
     */
    protected $requiredFields = [
        'date',
        'paymentMode',
        'amount'
    ];

    /**
     * @var \DateTime
     */
    protected $date;

    /**
     * @var string
     */
    protected $paymentMode;

    /**
     * @var double
     */
    protected $amount;

    /**
     * @var string
     */
    protected $description;
}
