<?php

declare(strict_types = 1);

namespace Cheppers\SzamlazzClient\DataType\Buyer;

use Cheppers\SzamlazzClient\DataType\BuyerLedger;

class InvoiceBuyer extends BuyerBase
{
    /**
     * {@inheritdoc}
     */
    protected static $propertyMapping = [
        'name'          => 'nev',
        'country'       => 'orszag',
        'zip'           => 'irsz',
        'city'          => 'telepules',
        'address'       => 'cim',
        'email'         => 'email',
        'sendEmail'     => 'sendEmail',
        'taxPayer'      => 'adoalany',
        'taxNumber'     => 'adoszam',
        'taxNumberEU'   => 'adoszamEU',
        'postalName'    => 'postazasiNev',
        'postalCountry' => 'postazasiOrszag',
        'postalZip'     => 'postazasiIrsz',
        'postalCity'    => 'postazasiTelepules',
        'postalAddress' => 'postazasiCim',
        'buyerLedger'   => 'vevoFokonyv',
        'id'            => 'azonosito',
        'signatoryName' => 'alairoNeve',
        'phoneNumber'   => 'telefonszam',
        'comment'       => 'megjegyzes',
    ];

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $country;

    /**
     * @var string
     */
    public $zip;

    /**
     * @var string
     */
    public $city;

    /**
     * @var string
     */
    public $address;

    /**
     * @var bool
     */
    public $sendEmail;

    /**
     * @var int
     */
    public $taxPayer;

    /**
     * @var string
     */
    public $taxNumber;

    /**
     * @var string
     */
    public $taxNumberEU;

    /**
     * @var string
     */
    public $postalName;

    /**
     * @var string
     */
    public $postalCountry;

    /**
     * @var string
     */
    public $postalZip;

    /**
     * @var string
     */
    public $postalCity;

    /**
     * @var string
     */
    public $postalAddress;

    /**
     * @var BuyerLedger
     */
    public $buyerLedger;

    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $signatoryName;

    /**
     * @var string
     */
    public $phoneNumber;

    /**
     * @var string
     */
    public $comment;

    /**
     * {@inheritdoc}
     */
    protected $requiredFields = [
        'name',
        'zip',
        'city',
        'address',
    ];
}
