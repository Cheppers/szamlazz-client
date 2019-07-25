<?php

declare(strict_types=1);

namespace Cheppers\SzamlazzClient\DataType\Response;

use Cheppers\SzamlazzClient\DataType\Base;

class InvoiceXMLResponse extends Base
{

    /**
     * {@inheritdoc}
     */
    protected static $propertyMapping = [
        'success'        => 'sikeres',
        'errorCode'      => 'hibakod',
        'errorMessage'   => 'hibauzenet',
        'invoiceNumber'  => 'szamlaszam',
        'netPrice'       => 'szamlanetto',
        'grossAmount'    => 'szamlabrutto',
        'userAccountUrl' => 'vevoifiokurl',
        'pdfData'        => 'pdf',
    ];

    /**
     * @var bool
     */
    public $success;

    /**
     * @var string
     */
    public $errorCode;

    /**
     * @var string
     */
    public $errorMessage;

    /**
     * @var string
     */
    public $invoiceNumber;

    /**
     * @var int
     */
    public $netPrice;

    /**
     * @var int
     */
    public $grossAmount;

    /**
     * @var string
     */
    public $userAccountUrl;

    /**
     * @var string
     */
    public $pdfData;
}
