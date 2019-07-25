<?php

declare(strict_types=1);

namespace Cheppers\SzamlazzClient\DataType;

class Settings extends Base
{

    /**
     * {@inheritdoc}
     */
    public $complexTypeName = 'beallitasok';

    /**
     * {@inheritdoc}
     */
    protected static $propertyMapping = [
        'username'           => 'felhasznalo',
        'password'           => 'jelszo',
        'eInvoice'           => 'eszamla',
        'keychainPassword'   => 'kulcstartojelszo',
        'invoiceDownload'    => 'szamlaLetoltes',
        'invoiceDownloadPld' => 'szamlaLetoltesPld',
        'responseVersion'    => 'valaszVerzio',
        'aggregator'         => 'aggregator',
        'invoiceNumber'      => 'szamlaszam',
        'additive'           => 'additiv',
        'pdfDownload'        => 'pdfLetoltes',
    ];

    /**
     * {@inheritdoc}
     */
    protected $requiredFields = [
       'username',
       'password',
       'eInvoice',
       'invoiceDownload',
    ];

    /**
     * @var string
     */
    public $username;

    /**
     * @var string
     */
    public $password;

    /**
     * @var bool
     */
    public $eInvoice;

    /**
     * @var string
     */
    public $keychainPassword;

    /**
     * @var bool
     */
    public $invoiceDownload;

    /**
     * @var int
     */
    public $invoiceDownloadPld;

    /**
     * @var int
     */
    public $responseVersion;

    /**
     * @var string
     */
    public $aggregator;

    /**
     * @var string
     */
    public $invoiceNumber;

    /**
     * @var bool
     */
    public $additive;

    /**
     * @var bool
     */
    public $pdfDownload;
}
