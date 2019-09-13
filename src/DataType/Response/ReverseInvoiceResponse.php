<?php

declare(strict_types = 1);

namespace Cheppers\SzamlazzClient\DataType\Response;

use Psr\Http\Message\ResponseInterface;

class ReverseInvoiceResponse
{
    /**
     * @var string[]
     */
    protected static $propertyMapping = [
        'debit'           => 'szlahu_kintlevoseg',
        'buyerAccountUrl' => 'szlahu_vevoifiokurl',
        'nettoTotal'      => 'szlahu_nettovegosszeg',
        'accountNumber'   => 'szlahu_szamlaszam',
        'grossTotal'      => 'szlahu_bruttovegosszeg',
        'errorMessage'    => 'szlahu_error',
        'errorCode'       => 'szlahu_error_code',
    ];

    /**
     * @todo Use parameters (array $header, ?string $body).
     */
    public static function __set_state(ResponseInterface $response): ReverseInvoiceResponse
    {
        $instance = new static();

        foreach ($response->getHeaders() as $key => $values) {
            if (!is_array($values)) {
                continue;
            }

            $internal = array_search($key, static::$propertyMapping);
            if ($internal === false) {
                continue;
            }

            switch ($key) {
                case 'szlahu_kintlevoseg':
                case 'szlahu_nettovegosszeg':
                case 'szlahu_bruttovegosszeg':
                case 'szlahu_error_code':
                    $instance->{$internal} = (int) $values[0];
                    break;

                default:
                    $instance->{$internal} = $values[0];
                    break;
            }
        }

        $instance->pdfData = $response->getBody()->getContents();

        return $instance;
    }

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
}
