<?php

declare(strict_types=1);

namespace Cheppers\SzamlazzClient\DataType;

class Buyer
{

    /**
     * @var string
     */
    public $id = '';

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
    public $zipCode;

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
    public $email = '';

    /**
     * @var int
     */
    public $taxPayerTypeId = 0;

    /**
     * @var string
     */
    public $taxNumber = '';

    /**
     * @var string
     */
    public $taxNumberEU = '';

    /**
     * @var string
     */
    public $postalName = '';

    /**
     * @var string
     */
    public $postalCountry = '';

    /**
     * @var string
     */
    public $postalZipCode = '';

    /**
     * @var string
     */
    public $postalCity = '';

    /**
     * @var string
     */
    public $postalAddress = '';

    /**
     * @var BuyerLedger
     */
    public $ledgerBuyerData;

    /**
     * @var string
     */
    public $signatoryName = '';

    /**
     * @var string
     */
    public $phone = '';

    /**
     * @var string
     */
    public $comment = '';

    public function __construct(string $name, string $country, string $zipCode, string $city, string $address)
    {
        $this->name = $name;
        $this->country = $country;
        $this->zipCode = $zipCode;
        $this->city = $city;
        $this->address = $address;
    }
}
