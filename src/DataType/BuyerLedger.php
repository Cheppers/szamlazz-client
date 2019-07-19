<?php

declare(strict_types=1);

namespace Cheppers\SzamlazzClient\DataType;

class BuyerLedger
{

    /**
     * @var string
     */
    public $buyerId = '';

    /**
     * @var string
     */
    public $bookingDate = '';

    /**
     * @var string
     */
    public $buyerLedgerNumber = '';

    /**
     * @var boolean
     */
    public $continuedFulfillment = false;

    public function __construct(
        string $buyerId,
        string $bookingDate,
        string $buyerLedgerNumber,
        string $continuedFulfillment
    ) {
        $this->buyerId = $buyerId;
        $this->bookingDate = $bookingDate;
        $this->buyerLedgerNumber = $buyerLedgerNumber;
        $this->continuedFulfillment = $continuedFulfillment;
    }
}
