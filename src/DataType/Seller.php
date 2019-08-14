<?php

declare(strict_types=1);

namespace Cheppers\SzamlazzClient\DataType;

class Seller extends Base
{

    /**
     * {@inheritdoc}
     */
    protected $complexTypeName = 'elado';

    /**
     * {@inheritdoc}
     */
    protected static $propertyMapping = [
        'bank'              => 'bank',
        'bankAccountNumber' => 'bankszamlaszam',
        'emailReplyTo'      => 'emailReplyto',
        'emailSubject'      => 'emailTargy',
        'emailBody'         => 'emailSzoveg',
        'signerName'        => 'alairoNeve',
    ];

    protected $requiredFields = [];

    /**
     * @var string
     */
    public $bank;

    /**
     * @var string
     */
    public $bankAccountNumber;

    /**
     * @var string
     */
    public $emailReplyTo;

    /**
     * @var string
     */
    public $emailSubject;

    /**
     * @var string
     */
    public $emailBody;

    /**
     * @var string
     */
    public $signerName;
}
