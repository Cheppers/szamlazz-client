<?php

declare(strict_types = 1);

namespace Cheppers\SzamlazzClient\DataType\Response;

use Psr\Http\Message\ResponseInterface;

class ReverseInvoiceResponse
{
    /**
     * @var int
     */
    public $debit;

    /**
     * @var string
     */
    public $buyerAccountUrl;

    /**
     * @var int
     */
    public $nettoTotal;

    /**
     * @var string
     */
    public $accountNumber;

    /**
     * @var int
     */
    public $grossTotal;

    /**
     * @var string
     */
    public $errorMessage;

    /**
     * @var int
     */
    public $errorCode;

    /**
     * @var string
     */
    public $pdfData;

    protected static $propertyMapping = [
        'debit'           => 'szlahu_kintlevoseg',
        'buyerAccountUrl' => 'szlahu_vevoifiokurl',
        'nettoTotal'      => 'szlahu_nettovegosszeg',
        'accountNumber'   => 'szlahu_szamlaszam',
        'grossTotal'      => 'szlahu_bruttovegosszeg',
        'errorMessage'    => 'szlahu_error',
        'errorCode'       => 'szlahu_error_code',
    ];

    public static function __set_state(ResponseInterface $response): ReverseInvoiceResponse
    {
        $instance = new static();

        foreach ($response->getHeaders() as $key => $values) {
            if (!is_array($values)) {
                continue;
            }

            switch ($key) {
                case 'szlahu_kintlevoseg':
                    $instance->debit = $values[0];
                    break;

                case 'szlahu_vevoifiokurl':
                    $instance->buyerAccountUrl = $values[0];
                    break;

                case 'szlahu_nettovegosszeg':
                    $instance->nettoTotal = $values[0];
                    break;

                case 'szlahu_szamlaszam':
                    $instance->accountNumber = $values[0];
                    break;

                case 'szlahu_bruttovegosszeg':
                    $instance->grossTotal = $values[0];
                    break;

                case 'szlahu_error':
                    $instance->errorMessage = $values[0];
                    break;

                case 'szlahu_error_code':
                    $instance->errorCode = $values[0];
                    break;
            }
        }

        $instance->pdfData = $response->getBody()->getContents();

        return $instance;
    }
}
