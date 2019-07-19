<?php

declare(strict_types=1);

namespace Cheppers\SzamlazzClient\DataType;

class Seller
{

    /**
     * @var string
     */
    public $bankName;

    /**
     * @var string
     */
    public $bankAccountNumber;

    /**
     * @var string
     */
    public $emailReplyTo = '';

    /**
     * @var string
     */
    public $emailSubject = '';

    /**
     * @var string
     */
    public $emailContent = '';

    /**
     * @var string
     */
    public $signatoryName = '';

    public function __construct(string $bankName, string $bankAccountNumber)
    {
        $this->bankName = $bankName;
        $this->bankAccountNumber = $bankAccountNumber;
    }
}
