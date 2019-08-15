<?php

declare(strict_types=1);

namespace Cheppers\SzamlazzClient\Tests\Unit\DataType\Response;

use Cheppers\SzamlazzClient\DataType\Address;
use Cheppers\SzamlazzClient\DataType\Response\TaxPayerResponse;
use DOMDocument;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Cheppers\SzamlazzClient\DataType\Response\TaxPayerResponse
 */
class TaxPayerResponseTest extends TestCase
{

    public function casesSetStateSuccess(): array
    {
        $address = new Address();
        $address->countryCode = 'HU';
        $address->postalCode = '8154';
        $address->city = 'POLGÁRDI';
        $address->streetName = 'Test';
        $address->publicPlaceCategory = 'Street';
        $address->number = '42';
        $address->building = '1';
        $address->staircase = 'A';
        $address->floor = 'B';
        $address->door = '2';

        return [
            'empty' => [
                [
                    'taxpayerName' => null,
                ],
                implode(PHP_EOL, [
                '<?xml version="1.0" encoding="UTF-8"?>',
                '<QueryTaxpayerResponse></QueryTaxpayerResponse>',
                ]),
            ],
            'basic' => [
                [
                    'requestId' => 'test_request_id',
                    'timestamp' => '2019-07-25T13:30:18.183Z',
                    'requestVersion' => '1.1',
                    'taxpayerValidity' => false,
                    'funcCode' => 'OK',
                    'errorCode' => 42,
                    'message' => 'test message',
                    'taxpayerName' => 'Test Taxpayer',
                    'address' => $address,
                ],
                implode(PHP_EOL, [
                    '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>',
                    '<QueryTaxpayerResponse',
                    '    xmlns="http://schemas.nav.gov.hu/OSA/1.0/api"',
                    '    xmlns:ns2="http://schemas.nav.gov.hu/OSA/1.0/data">',
                    '    <header>',
                    '        <requestId>test_request_id</requestId>',
                    '        <timestamp>2019-07-25T13:30:18.183Z</timestamp>',
                    '        <requestVersion>1.1</requestVersion>',
                    '    </header>',
                    '    <result>',
                    '        <funcCode>OK</funcCode>',
                    '        <errorCode>42</errorCode>',
                    '        <message>test message</message>',
                    '    </result>',
                    '    <taxpayerValidity>false</taxpayerValidity>',
                    '    <taxpayerData>',
                    '        <taxpayerName>Test Taxpayer</taxpayerName>',
                    '        <taxpayerAddress>',
                    '            <countryCode>HU</countryCode>',
                    '            <postalCode>8154</postalCode>',
                    '            <city>POLGÁRDI</city>',
                    '            <streetName>Test</streetName>',
                    '            <publicPlaceCategory>Street</publicPlaceCategory>',
                    '            <number>42</number>',
                    '            <building>1</building>',
                    '            <staircase>A</staircase>',
                    '            <floor>B</floor>',
                    '            <door>2</door>',
                    '        </taxpayerAddress>',
                    '    </taxpayerData>',
                    '</QueryTaxpayerResponse>'
                ])
            ],
            'not xml element' => [
                [
                    'taxpayerName' => null,
                ],
                implode(PHP_EOL, [
                    '<?xml version="1.0" encoding="UTF-8"?>',
                    '<QueryTaxpayerResponse>',
                        '    <header>',
                        '    </header>',
                    '</QueryTaxpayerResponse>',
                ]),
            ],
        ];
    }

    /**
     * @dataProvider casesSetStateSuccess
     */
    public static function testSetStateSuccess(array $expected, string $xml)
    {
        $doc = new DOMDocument();
        $doc->loadXML($xml);

        /** @var \DOMElement $root */
        $root = $doc->getElementsByTagName('QueryTaxpayerResponse')->item(0);
        $instance = TaxPayerResponse::__set_state($root);

        foreach ($expected as $name => $value) {
            static::assertEquals($value, $instance->{$name});
        }
    }
}
