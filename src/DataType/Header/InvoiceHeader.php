<?php

declare(strict_types=1);

namespace Cheppers\SzamlazzClient\DataType\Header;

use Cheppers\SzamlazzClient\DataType\Base;

class InvoiceHeader extends Base
{

    /**
     * {@inheritdoc}
     */
    protected $complexTypeName = 'fejlec';

    /**
     * {@inheritdoc}
     */
    protected static $propertyMapping = [
        'issueDate'         => 'keltDatum',
        'fulfillmentDate'   => 'teljesitesDatum',
        'paymentDue'        => 'fizetesiHataridoDatum',
        'paymentMethod'     => 'fizmod',
        'currency'          => 'penznem',
        'invoiceLanguage'   => 'szamlaNyelve',
        'comment'           => 'megjegyzes',
        'exchangeBank'      => 'arfolyamBank',
        'exchangeRate'      => 'arfolyam',
        'orderNumber'       => 'rendelesSzam',
        'proformaNumber'    => 'dijbekeroSzamlaszam',
        'depositBill'       => 'elolegszamla',
        'finalBill'         => 'vegszamla',
        'creditInvoice'     => 'helyesbitoszamla',
        'correctiveNumber'  => 'helyesbitettSzamlaszam',
        'prepaymentRequest' => 'dijbekero',
        'deliveryNote'      => 'szallitolevel',
        'logoExtra'         => 'logoExtra',
        'billNumberPrefix'  => 'szamlaszamElotag',
        'correctionToPay'   => 'fizetendoKorrekcio',
        'paid'              => 'fizetve',
        'profitVat'         => 'arresAfa',
    ];

    /**
     * {@inheritdoc}
     */
    protected $requiredFields = [
       'issueDate',
       'fulfillmentDate',
       'paymentDue',
       'paymentMethod',
       'currency',
       'invoiceLanguage',
    ];

    /**
     * @var \DateTime
     */
    public $issueDate;

    /**
     * @var \DateTime
     */
    public $fulfillmentDate;

    /**
     * @var \DateTime
     */
    public $paymentDue;

    /**
     * @var string
     */
    public $paymentMethod;

    /**
     * @var string
     */
    public $currency;

    // @todo create InvoiceLanguageType for this property
    public $invoiceLanguage;

    /**
     * @var string
     */
    public $comment;

    /**
     * @var string
     */
    public $exchangeBank;

    /**
     * @var double
     */
    public $exchangeRate;

    /**
     * @var string
     */
    public $orderNumber;

    /**
     * @var string
     */
    public $proformaNumber;

    /**
     * @var bool
     */
    public $depositBill;

    /**
     * @var bool
     */
    public $finalBill;

    /**
     * @var bool
     */
    public $creditInvoice;

    /**
     * @var string
     */
    public $correctiveNumber;

    /**
     * @var bool
     */
    public $prepaymentRequest;

    /**
     * @var bool
     */
    public $deliveryNote;

    /**
     * @var string
     */
    public $logoExtra;

    /**
     * @var string
     */
    public $billNumberPrefix;

    /**
     * @var double
     */
    public $correctionToPay;

    /**
     * @var bool
     */
    public $paid;

    /**
     * @var bool
     */
    public $profitVat;
}
