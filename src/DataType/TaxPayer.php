<?php

declare(strict_types=1);

namespace Cheppers\SzamlazzClient\DataType;

class TaxPayer extends Base
{
    /**
     * @var string
     */
    public $taxpayerId;

    /**
     * @var int
     */
    public $taxpayerTypeId;

    /**
     * @var string[]
     */
    public $requiredFields = ['taxPayerId'];

    protected static $propertyMapping = [
        'taxpayerId' => 'torzsszam',
    ];

    public function exportData(): array
    {
        $data = [];
        foreach (static::$propertyMapping as $internal => $external) {
            $value =  $this->{$internal};
            if (!in_array($internal, $this->requiredFields) && !$value) {
                continue;
            }

            $data[$external] = $value;
        }

        return $data;
    }
}
