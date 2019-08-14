<?php

namespace Cheppers\SzamlazzClient\Tests\Unit\DataType;

use Cheppers\SzamlazzClient\DataType\QueryTaxpayer;
use Cheppers\SzamlazzClient\DataType\Settings\SettingsBase;
use Exception;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Cheppers\SzamlazzClient\DataType\QueryTaxpayer
 */
class QueryTaxpayerTest extends TestCase
{
    public function casesSetState()
    {
        $basicQueryTaxpayer = new QueryTaxpayer();
        $settings = new SettingsBase();
        $settings->apiKey = 'myApiKey';
        $basicQueryTaxpayer->settings = $settings;
        $basicQueryTaxpayer->taxpayerId = 11111111;

        return [
            'empty' => [
                new QueryTaxpayer(),
                [
                    'empty' => [
                        'key' => 'value',
                    ]
                ],
            ],
            'basic' =>[
                $basicQueryTaxpayer,
                [
                    'settings' => [
                        'apiKey' => 'myApiKey',
                    ],
                    'taxpayerId' => 11111111,
                ],
            ]
        ];
    }

    /**
     * @dataProvider casesSetState
     */
    public function testSetState(QueryTaxpayer $expected, array $data)
    {
        $instance = QueryTaxpayer::__set_state($data);

        static::assertEquals($expected, $instance);
    }

    public function casesBuildXmlStringSuccess()
    {
        $basicQueryTaxpayer = new QueryTaxpayer();
        $settings = new SettingsBase();
        $settings->apiKey = 'my-api-key';
        $basicQueryTaxpayer->settings = $settings;
        $basicQueryTaxpayer->taxpayerId = 12345678;

        return [
            'basic' => [
                implode('', [
                    "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n",
                    implode(' ', [
                        '<xmltaxpayer xmlns="http://www.szamlazz.hu/xmltaxpayer"',
                        'xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"',
                        'xsi:schemaLocation="http://www.szamlazz.hu/xmltaxpayer',
                        'http://www.szamlazz.hu/szamla/docs/xsds/taxpayer/xmltaxpayer.xsd">',
                    ]),
                    '<beallitasok>',
                    '<szamlaagentkulcs>my-api-key</szamlaagentkulcs>',
                    '</beallitasok>',
                    '<torzsszam>12345678</torzsszam>',
                    "</xmltaxpayer>\n",
                ]),
                $basicQueryTaxpayer,
            ],
        ];
    }

    /**
     * @dataProvider casesBuildXmlStringSuccess
     *
     * @throws Exception
     */
    public function testBuildXmlStringSuccess(string $expected, QueryTaxpayer $queryTaxpayer)
    {
        $actual = $queryTaxpayer->buildXmlString();

        static::assertSame($expected, $actual);
    }

    public function casesBuildXmlStringFailed()
    {
        return [
            'basic' => [
                new Exception('Missing required field'),
                new QueryTaxpayer(),
            ],
        ];
    }

    /**
     * @dataProvider casesBuildXmlStringFailed
     * @throws Exception
     */
    public function testBuildXmlStringFailed(Exception $expected, QueryTaxpayer $queryTaxpayer)
    {
        static::expectExceptionObject($expected);
        $queryTaxpayer->buildXmlString();
    }
}
