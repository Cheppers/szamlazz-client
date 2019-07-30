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

    public function isEmpty(): bool
    {
        return $this->apiKey === null;
    }
}
