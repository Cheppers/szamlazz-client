<?php

declare(strict_types = 1);

namespace Cheppers\SzamlazzClient\DataType\Settings;

class ReverseInvoiceSettings extends SettingsBase
{
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
     * {@inheritdoc}
     */
    protected $requiredFields = [
        'apiKey',
        'eInvoice',
        'invoiceDownload'
    ];

    protected static $propertyMapping = [
        'apiKey'               => 'szamlaagentkulcs',
        'eInvoice'             => 'eszamla',
        'keychainPassword'     => 'kulcstartojelszo',
        'invoiceDownload'      => 'szamlaLetoltes',
        'invoiceDownloadCount' => 'szamlaLetoltesPld',
    ];
}
