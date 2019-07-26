<?php

declare(strict_types=1);

namespace Cheppers\SzamlazzClient\Tests\Unit\DataType\Response;

use Cheppers\SzamlazzClient\DataType\Response\TaxPayerResponse;
use PHPUnit\Framework\TestCase;

class TaxPayerResponseTest extends TestCase
{

    public function casesSetState(): array
    {
        return [
            'empty' => [
                [
                    'taxPayerName' => null,
                ],
                implode(PHP_EOL, [
                '<?xml version="1.0" encoding="UTF-8"?>',
                '<QueryTaxpayerResponse></QueryTaxpayerResponse>',
                ]),
            ],
            'basic' => [
                [
                    'taxPayerValidity' => 'false',
                    'taxPayerName' => 'Test Taxpayer',
                    'requestId' => 'test_request_id',
                    'timestamp' => '2019-07-25T13:30:18.183Z',
                    'address' => [
                        'countryCode' => 'HU',
                        'postalCode' => '8154',
                        'city' => 'POLGÁRDI',
                        'streetName' => 'Test',
                        'publicPlaceCategory' => 'Street',
                        'number' => '42',
                        'building' => '1',
                        'staircase' => 'A',
                        'floor' => 'B',
                        'door' => '2',
                    ],
                ],
                implode(PHP_EOL, [
                    '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>',
                    '<QueryTaxpayerResponse xmlns="http://schemas.nav.gov.hu/OSA/1.0/api" xmlns:ns2="http://schemas.nav.gov.hu/OSA/1.0/data">',
                    '<header>',
                    '<requestId>test_request_id</requestId>',
                    '<timestamp>2019-07-25T13:30:18.183Z</timestamp>',
                    '<requestVersion>1.1</requestVersion>',
                    '</header>',
                    '<result>',
                    '<funcCode>OK</funcCode>',
                    '</result>',
                    '<taxpayerValidity>false</taxpayerValidity>',
                    '<taxpayerData>',
                    '<taxpayerName>Test Taxpayer</taxpayerName>',
                    '<taxpayerAddress>',
                    '<countryCode>HU</countryCode>',
                    '<postalCode>8154</postalCode>',
                    '<city>POLGÁRDI</city>',
                    '<streetName>Test</streetName>',
                    '<publicPlaceCategory>Street</publicPlaceCategory>',
                    '<number>42</number>',
                    '<building>1</building>',
                    '<staircase>A</staircase>',
                    '<floor>B</floor>',
                    '<door>2</door>',
                    '</taxpayerAddress>',
                    '</taxpayerData>',
                    '</QueryTaxpayerResponse>'
                ])
            ],
        ];
    }

    /**
     * @dataProvider casesSetState
     */
    public static function testSetState(array $expected, string $xml)
    {
        $doc = new \DOMDocument();
        $doc->loadXML($xml);

        $instance = TaxPayerResponse::__set_state($doc);

        foreach ($expected as $name => $value) {
            static::assertEquals($value, $instance->{$name});
        }
    }
}
