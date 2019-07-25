<?php

declare(strict_types=1);

namespace Cheppers\SzamlazzClient\DataType\CreditNote;

use Cheppers\SzamlazzClient\DataType\Base;

class ReceiptCreditNote extends Base
{

    /**
     * {@inheritdoc}
     */
    protected $complexTypeName = 'kifizetes';

    /**
     * {@inheritdoc}
     */
    protected static $propertyMapping = [
        'paymentMode' => 'fizetoeszkoz',
        'amount'      => 'osszeg',
        'description' => 'leiras',
    ];

    /**
     * {@inheritdoc}
     */
    protected $requiredFields = [
        'fizetoeszkoz',
        'osszeg',
    ];

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
