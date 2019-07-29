<?php

declare(strict_types=1);

namespace Cheppers\SzamlazzClient\DataType;

abstract class Base
{

    /**
     * @var string
     */
    protected $complexTypeName;

    /**
     * @var string[]
     */
    protected static $propertyMapping = [];

    /**
     * Internal name of the required fields.
     *
     * @var string[]
     */
    protected $requiredFields = [];

    public static function __set_state($values)
    {
        $instance = new static();

        foreach ($values as $key => $value) {
            if (!property_exists($instance, $key)) {
                continue;
            }
            $instance->{$key} = $value;
        }

        return $instance;
    }

    public function exportData(): array
    {
        $data = [];
        foreach (static::$propertyMapping as $internal => $external) {
            $value =  $this->{$internal};
            if (!in_array($internal, $this->requiredFields) && !$value) {
                continue;
            }

            $data[$this->complexTypeName][$external] = $value;
        }

        return $data;
    }
}
