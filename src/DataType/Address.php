<?php

declare(strict_types=1);

namespace Cheppers\SzamlazzClient\DataType;

class Address extends Base
{
    /**
     * @var string
     */
    public $countryCode;

    /**
     * @var string
     */
    public $postalCode;

    /**
     * @var string
     */
    public $city;

    /**
     * @var string
     */
    public $streetName;

    /**
     * @var string
     */
    public $publicPlaceCategory;

    /**
     * @var string
     */
    public $number;

    /**
     * @var string
     */
    public $building;

    /**
     * @var string
     */
    public $staircase;

    /**
     * @var string
     */
    public $floor;

    /**
     * @var string
     */
    public $door;

    public static function __set_state(\DOMNode $doc)
    {
        $instance = new static();

        foreach ($doc->childNodes as $element) {
            if ($element->nodeType !== XML_ELEMENT_NODE) {
                continue;
            }
            $instance->{$element->nodeName} = $element->nodeValue;
        }

        return $instance;
    }
}
