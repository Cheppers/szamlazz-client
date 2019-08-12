<?php

declare(strict_types=1);

namespace Cheppers\SzamlazzClient\DataType;

class Buyer extends Base
{

    /**
     * {@inheritdoc}
     */
    protected $complexTypeName = 'vevo';

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
     * {@inheritdoc}
     */
    protected $requiredFields = [
        'name',
        'zip',
        'city',
        'address',
    ];

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
     * @var string
     */
    public $email;

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
     * @var \Cheppers\SzamlazzClient\DataType\BuyerLedger
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

    public function isEmpty(): bool
    {
        return $this->name === null;
    }
}
