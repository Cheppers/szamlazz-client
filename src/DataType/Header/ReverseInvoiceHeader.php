<?php

declare(strict_types = 1);

namespace Cheppers\SzamlazzClient\DataType\Header;

use Cheppers\SzamlazzClient\DataType\Base;

class ReverseInvoiceHeader extends Base
{

    /**
     * {@inheritdoc}
     */
    protected $complexTypeName = 'fejlec';

    /**
     * {@inheritdoc}
     */
    protected static $propertyMapping = [
        'accountNumber'     => 'szamlaszam',
        'issueDate'         => 'keltDatum',
        'fulfillmentDate'   => 'teljesitesDatum',
        'type'              => 'tipus',
    ];

    /**
     * {@inheritdoc}
     */
    protected $requiredFields = ['accountNumber'];

    /**
     * @var string
     */
    public $accountNumber;

    /**
     * @var \DateTime
     */
    public $issueDate;

    /**
     * @var \DateTime
     */
    public $fulfillmentDate;

    /**
     * @var string
     */
    public $type;

    public function isEmpty(): bool
    {
        return $this->accountNumber === null;
    }
}
