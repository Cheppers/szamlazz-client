<?php

declare(strict_types = 1);

namespace Cheppers\SzamlazzClient\DataType\Settings;

use Cheppers\SzamlazzClient\DataType\Base;

class SettingsBase extends Base
{
    /**
     * @var string
     */
    public $apiKey;

    /**
     * {@inheritdoc}
     */
    public $complexTypeName = 'beallitasok';

    protected static $propertyMapping = [
        'apiKey' => 'szamlaagentkulcs',
    ];

    protected $requiredFields = ['apiKey'];

    public function isEmpty(): bool
    {
        foreach ($this->requiredFields as $field) {
            if (!isset($this->{$field})) {
                return true;
            }
        }

        return false;
    }
}
