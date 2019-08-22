<?php

declare(strict_types = 1);

namespace Cheppers\SzamlazzClient\DataType\Response;

use DOMElement;

class InvoiceResponse
{
    /**
     * @var string[]
     */
    protected static $propertyMapping = [
        'success'       => 'sikeres',
        'errorCode'     => 'hibakod',
        'errorMessage'  => 'hibauzenet',
        'invoiceNumber' => 'szamlaszam',
        'netPrice'      => 'szamlanetto',
        'grossAmount'   => 'szamlabrutto',
        'pdfData'       => 'pdf',
    ];

    public static function __set_state(DOMElement $root): InvoiceResponse
    {
        $instance = new static();

        /** @var \DOMElement $element */
        foreach ($root->childNodes as $element) {
            if ($element->nodeType !== XML_ELEMENT_NODE) {
                continue;
            }

            $internal = array_search($element->nodeName, static::$propertyMapping);
            if ($internal === false) {
                continue;
            }

            switch ($element->nodeName) {
                case 'sikeres':
                    $instance->{$internal} = $element->nodeValue === 'true' ? true : false;
                    break;

                case 'hibakod':
                    $instance->{$internal} = (int) $element->nodeValue;
                    break;

                default:
                    $instance->{$internal} = $element->nodeValue;
                    break;
            }
        }

        return $instance;
    }

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

    /**
     * @var string
     */
    public $invoiceNumber;

    /**
     * @var int
     */
    public $netPrice;

    /**
     * @var int
     */
    public $grossAmount;

    /**
     * @var string
     */
    public $pdfData;
}
