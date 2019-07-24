<?php

declare(strict_types=1);

namespace Cheppers\SzamlazzClient\DataType;

class InvoicePdf extends Base
{

    /**
     * @var string
     */
    public $invoiceId;

    /**
     * @var int
     */
    public $responseType;

    /**
     * {@inheritdoc}
     */
    protected $parents = [
        'settingsType' => [
            'invoiceId',
            'responseType',
        ],
    ];
}
