<?php

namespace Cheppers\SzamlazzClient\Tests\Unit\DataType;

use Cheppers\SzamlazzClient\DataType\Address;
use PHPUnit\Framework\TestCase;

class AddressTest extends TestCase
{
    public function casesSetState()
    {
        return [
            'empty' => [
                [
                    'postalCode' => null,
                ],
                implode(PHP_EOL, [
                    '<?xml version="1.0" encoding="UTF-8"?>',
                    '<QueryTaxpayerResponse>',
                    '<taxpayerAddress></taxpayerAddress>',
                    '</QueryTaxpayerResponse>',
                    ])
            ],
            'basic' => [
                [
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
                implode(PHP_EOL, [
                    '<?xml version="1.0" encoding="UTF-8"?>',
                    '<QueryTaxpayerResponse>',
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
                    '</QueryTaxpayerResponse>',
                ])
            ]
        ];
    }

    /**
     * @dataProvider casesSetState
     */
    public static function testSetState($expected, string $xml)
    {
        $doc = new \DOMDocument();
        $doc->loadXML($xml);
        $elements = $doc->getElementsByTagName('taxpayerAddress')->item(0);

        $instance = Address::__set_state($elements);

        foreach ($expected as $name => $value) {
            static::assertEquals($value, $instance->{$name});
        }
    }
}
