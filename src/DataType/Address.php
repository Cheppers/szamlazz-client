<?php

declare(strict_types = 1);

namespace Cheppers\SzamlazzClient\DataType;

class Address extends Base
{

    /**
     * @param \DOMElement $root
     */
    public static function __set_state($root)
    {
        $instance = new static();

        foreach ($root->childNodes as $element) {
            if ($element->nodeType !== XML_ELEMENT_NODE || !property_exists($instance, $element->nodeName)) {
                continue;
            }

            switch ($element->nodeName) {
                case 'countryCode':
                case 'postalCode':
                case 'city':
                case 'streetName':
                case 'publicPlaceCategory':
                case 'number':
                case 'building':
                case 'staircase':
                case 'floor':
                case 'door':
                    $instance->{$element->nodeName} = $element->nodeValue;
                    break;
            }
        }

        return $instance;
    }

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

    /**
     * {@inheritdoc}
     */
    protected $requiredFields = ['countryCode'];
}
