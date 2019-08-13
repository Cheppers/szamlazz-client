<?php

namespace Cheppers\SzamlazzClient\Tests\Unit\DataType\Response;

use Cheppers\SzamlazzClient\DataType\Response\ReverseInvoiceResponse;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Cheppers\SzamlazzClient\DataType\Response\ReverseInvoiceResponse
 */
class ReverseInvoiceResponseTest extends TestCase
{
    public function casesSetState()
    {
        $reverseInvoiceBasic = new ReverseInvoiceResponse();
        $reverseInvoiceBasic->debit = 1000;
        $reverseInvoiceBasic->accountNumber = '1234567-88-9';
        $reverseInvoiceBasic->buyerAccountUrl = 'example.com';
        $reverseInvoiceBasic->nettoTotal = 2000;
        $reverseInvoiceBasic->grossTotal = 3000;
        $reverseInvoiceBasic->pdfData = 'base 64 pdf';
        $reverseInvoiceBasic->errorCode = 42;
        $reverseInvoiceBasic->errorMessage = 'Foo';

        return [
            'empty' => [
                new ReverseInvoiceResponse(),
                new Response(200, ['Bar']),
            ],
            'basic' => [
                $reverseInvoiceBasic,
                new Response(
                    200,
                    [
                        'szlahu_kintlevoseg' => 1000,
                        'szlahu_vevoifiokurl' => 'example.com',
                        'szlahu_nettovegosszeg' => 2000,
                        'szlahu_szamlaszam' => '1234567-88-9',
                        'szlahu_bruttovegosszeg' => 3000,
                        'szlahu_error' => 'Foo',
                        'szlahu_error_code' => 42,
                    ],
                    'base 64 pdf'
                )
            ]
        ];
    }

    /**
     * @dataProvider casesSetState
     */
    public function testSetState(ReverseInvoiceResponse $expected, Response $response)
    {
        $instance = ReverseInvoiceResponse::__set_state($response);

        foreach ($expected as $name => $value) {
            static::assertEquals($value, $instance->{$name});
        }
    }
}
