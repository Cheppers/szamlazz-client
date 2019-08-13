<?php

declare(strict_types = 1);

namespace Cheppers\SzamlazzClient\DataType\Buyer;

use Cheppers\SzamlazzClient\DataType\Base;

class BuyerBase extends Base
{
    /**
     * @var string
     */
    public $email;

    /**
     * {@inheritdoc}
     */
    protected $complexTypeName = 'vevo';

    /**
     * {@inheritdoc}
     */
    protected static $propertyMapping = [
        'email' => 'email',
    ];

    /**
     * {@inheritdoc}
     */
    protected $requiredFields = [
        'email',
    ];

    public function isEmpty(): bool
    {
        foreach ($this->requiredFields as $field) {
            if (!$this->{$field}) {
                return true;
            }
        }

        return false;
    }
}
