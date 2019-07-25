<?php

declare(strict_types=1);

namespace Cheppers\SzamlazzClient\DataType;

class TaxPayer extends Base
{
    const TAXPAYER_JOINT_VENTURE = 5;

    const TAXPAYER_INDIVIDUAL_BUSINESS = 4;

    const TAXPAYER_PRIVATE_INDIVIDUAL_WITH_TAXNUMBER = 3;

    const TAXPAYER_OTHER_ORGANIZATION_WITH_TAXNUMBER = 2;

    const TAXPAYER_HAS_TAXNUMBER = 1;

    const TAXPAYER_WE_DONT_KNOW = 0;

    const TAXPAYER_NO_TAXNUMBER = -1;

    const TAXPAYER_PRIVATE_INDIVIDUAL = -2;

    const TAXPAYER_OTHER_ORGANIZATION_WITHOUT_TAXNUMBER = -3;

    /**
     * @var string
     */
    public $taxPayerId;

    /**
     * @var int
     */
    public $taxPayerTypeId;

    /**
     * @var string
     */
    public $requiredFields = ['taxPayerId'];

    protected static $propertyMapping = [
        'taxPayerId' => 'torzsszam',
    ];

    public function __construct(string $taxPayerId, $taxPayerTypeId = 0)
    {
        $this->taxPayerId = $taxPayerId;
        $this->taxPayerTypeId = $taxPayerTypeId;
    }
}
