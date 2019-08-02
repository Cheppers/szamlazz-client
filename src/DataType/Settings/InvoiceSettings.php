<?php

declare(strict_types = 1);

namespace Cheppers\SzamlazzClient\DataType\Settings;

class InvoiceSettings extends ReverseInvoiceSettings
{
    /**
     * @var int
     */
    public $responseVersion;

    /**
     * @var string
     */
    public $aggregator;

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
    ];
}
