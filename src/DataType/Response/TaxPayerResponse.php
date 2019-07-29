<?php

declare(strict_types=1);

namespace Cheppers\SzamlazzClient\DataType\Response;

use Cheppers\SzamlazzClient\DataType\Address;
use Cheppers\SzamlazzClient\DataType\Base;

class TaxPayerResponse extends Base
{

    /**
     * @var string
     */
    public $requestId;

    /**
     * @var string
     */
    public $timestamp;

    /**
     * @var string
     */
    public $requestVersion;

    /**
     * @var string
     */
    public $funcCode;

    /**
     * @var string
     */
    public $taxpayerValidity;

    /**
     * @var string
     */
    public $taxpayerName;

    /**
     * @var \Cheppers\SzamlazzClient\DataType\Address
     */
    public $address;


    public static function __set_state(\DOMElement $root)
    {
        $instance = new static();

        /** @var \DOMElement $element */
        /** @var \DOMElement $subElement */
        foreach ($root->childNodes as $element) {
            if ($element->nodeType !== XML_ELEMENT_NODE) {
                continue;
            }

            switch ($element->nodeName) {
                case 'header':
                    foreach ($element->childNodes as $subElement) {
                        if ($element->nodeType !== XML_ELEMENT_NODE) {
                            continue;
                        }
                        switch ($subElement->nodeName) {
                            case 'requestId':
                                $instance->requestId = $subElement->nodeValue;
                                break;

                            case 'timestamp':
                                $instance->timestamp = $subElement->nodeValue;
                                break;

                            case 'requestVersion':
                                $instance->requestVersion = $subElement->nodeValue;
                                break;
                        }
                    }
                    break;

                case 'result':
                    foreach ($element->childNodes as $subElement) {
                        if ($element->nodeType !== XML_ELEMENT_NODE) {
                            continue;
                        }

                        switch ($subElement->nodeName) {
                            case 'funcCode':
                                $instance->funcCode = $subElement->nodeValue;
                                break;
                        }
                    }
                    break;

                case 'taxpayerValidity':
                    $instance->taxpayerValidity = $element->nodeValue;
                    break;

                case 'taxpayerData':
                    foreach ($element->childNodes as $subElement) {
                        if ($element->nodeType !== XML_ELEMENT_NODE) {
                            continue;
                        }

                        switch ($subElement->nodeName) {
                            case 'taxpayerName':
                                $instance->taxpayerName  = $subElement->nodeValue;
                                break;

                            case 'taxpayerAddress':
                                $instance->address = Address::__set_state($subElement);
                                break;
                        }
                    }
                    break;
            }
        }

        return $instance;
    }
}
