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
        'apiKey'               => 'szamlaagentkulcs',
        'eInvoice'             => 'eszamla',
        'keychainPassword'     => 'kulcstartojelszo',
        'invoiceDownload'      => 'szamlaLetoltes',
        'invoiceDownloadCount' => 'szamlaLetoltesPld',
        'responseVersion'      => 'valaszVerzio',
        'aggregator'           => 'aggregator',
        'invoiceNumber'        => 'szamlaszam',
        'additive'             => 'additiv',
        'pdfDownload'          => 'pdfLetoltes',
    ];

    /**
     * {@inheritdoc}
     */
    protected $requiredFields = [
        'apiKey',
    ];

    /**
     * @var string
     */
    public $apiKey;

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
    public $invoiceDownloadCount;

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
