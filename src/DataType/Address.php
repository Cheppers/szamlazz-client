<?php

declare(strict_types=1);

namespace Cheppers\SzamlazzClient\DataType;

class Address extends Base
{
    public static function __set_state($root)
    {
        $instance = new static();

        foreach ($root->childNodes as $element) {
            if ($element->nodeType !== XML_ELEMENT_NODE || !property_exists($instance, $element->nodeName)) {
                continue;
            }

            switch ($element->nodeName) {
                case 'countryCode':
                    $instance->countryCode = $element->nodeValue;
                    break;

                case 'postalCode':
                    $instance->postalCode = $element->nodeValue;
                    break;

                case 'city':
                    $instance->city = $element->nodeValue;
                    break;

                case 'streetName':
                    $instance->streetName = $element->nodeValue;
                    break;

                case 'publicPlaceCategory':
                    $instance->publicPlaceCategory = $element->nodeValue;
                    break;

                case 'number':
                    $instance->number = $element->nodeValue;
                    break;

                case 'building':
                    $instance->building = $element->nodeValue;
                    break;

                case 'staircase':
                    $instance->staircase = $element->nodeValue;
                    break;

                case 'floor':
                    $instance->floor = $element->nodeValue;
                    break;

                case 'door':
                    $instance->door = $element->nodeValue;
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
