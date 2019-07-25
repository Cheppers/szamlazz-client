<?php

declare(strict_types=1);

namespace Cheppers\SzamlazzClient\DataType\Response;

use Cheppers\SzamlazzClient\DataType\Base;

class ProformaDeletionResponse extends Base
{

    /**
     * {@inheritdoc}
     */
    protected static $propertyMapping = [
        'success'      => 'sikeres',
        'errorCode'    => 'hibakod',
        'errorMessage' => 'hibauzenet',
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
}
