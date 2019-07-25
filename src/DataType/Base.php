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

    public function buildXmlData()
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
