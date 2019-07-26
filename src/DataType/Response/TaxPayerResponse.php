<?php

declare(strict_types=1);

namespace Cheppers\SzamlazzClient\DataType\Response;

use Cheppers\SzamlazzClient\DataType\Address;
use Cheppers\SzamlazzClient\DataType\Base;

class TaxPayerResponse extends Base
{
    /**
     * @var bool
     */
    public $taxPayerValidity = false;

    /**
     * @var string
     */
    public $taxPayerName;

    /**
     * @var string
     */
    public $requestId;

    /**
     * @var string
     */
    public $timestamp;

    /**
     * @var \Cheppers\SzamlazzClient\DataType\Address
     */
    public $address;


    public static function __set_state(\DOMNode $doc)
    {
        $instance = new static();


        $xpath = new \DOMXPath($doc->ownerDocument ?: $doc);
        $elements = $xpath->query('/QueryTaxpayerResponse/TaxPayerData/*', $doc);
        for ($i = 0; $i < $elements->length; $i++) {
            $element = $elements->item($i);
            if ($element->nodeType !== XML_ELEMENT_NODE) {
                continue;
            }

            switch ($element->nodeName) {
                case 'taxPayerName':
                    $instance->taxPayerName = $element->nodeValue;
                    break;

                case 'taxPayerAddress':
                    $instance->address = Address::__set_state($element);
                    break;
            }
        }

        return $instance;
    }
}
