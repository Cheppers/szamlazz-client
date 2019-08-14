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
     * @return mixed
     */
    public function getComplexTypeName()
    {
        return $this->complexTypeName;
    }

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
}
