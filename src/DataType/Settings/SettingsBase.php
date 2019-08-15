<?php

declare(strict_types = 1);

namespace Cheppers\SzamlazzClient\DataType\Settings;

use Cheppers\SzamlazzClient\DataType\Base;

class SettingsBase extends Base
{
    /**
     * {@inheritdoc}
     */
    protected static $propertyMapping = [
        'apiKey' => 'szamlaagentkulcs',
    ];

    /**
     * @var string
     */
    public $apiKey;

    /**
     * {@inheritdoc}
     */
    public $complexTypeName = 'beallitasok';

    /**
     * {@inheritdoc}
     */
    protected $requiredFields = ['apiKey'];
}
