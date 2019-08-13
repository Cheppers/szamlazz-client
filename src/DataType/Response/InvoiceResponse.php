<?php

declare(strict_types=1);

namespace Cheppers\SzamlazzClient\DataType\Response;

use Cheppers\SzamlazzClient\DataType\Base;

class InvoiceResponse
{

    protected static $propertyMapping = [
        'success'         => 'sikeres',
        'errorCode'       => 'hibakod',
        'errorMessage'    => 'hibauzenet',
        'invoiceNumber'   => 'szamlaszam',
        'netPrice'        => 'szamlanetto',
        'grossAmount'     => 'szamlabrutto',
        'pdfData'         => 'pdf',
    ];

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

    public static function __set_state(\DOMElement $root): InvoiceResponse
    {
        $instance = new static();

        /** @var \DOMElement $element */
        /** @var \DOMElement $subElement */
        foreach ($root->childNodes as $element) {
            if ($element->nodeType !== XML_ELEMENT_NODE) {
                continue;
            }

            switch ($element->nodeName) {
                case 'sikeres':
                    $instance->success = $element->nodeValue === 'true' ? true : false;
                    break;

                case 'hibakod':
                    $instance->errorCode = (int) $element->nodeValue;
                    break;

                case 'hibauzenet':
                    $instance->errorMessage = $element->nodeValue;
                    break;

                case 'szamlaszam':
                    $instance->invoiceNumber = $element->nodeValue;
                    break;

                case 'szamlanetto':
                    $instance->netPrice = $element->nodeValue;
                    break;

                case 'szamlabrutto':
                    $instance->grossAmount = $element->nodeValue;
                    break;

                case 'pdf':
                    $instance->pdfData = $element->nodeValue;
                    break;
            }
        }

        return $instance;
    }
}
