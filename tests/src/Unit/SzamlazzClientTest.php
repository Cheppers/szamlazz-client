<?php

declare(strict_types = 1);

namespace Cheppers\SzamlazzClient\Tests\Unit;

use Cheppers\SzamlazzClient\DataType\Address;
use Cheppers\SzamlazzClient\DataType\QueryTaxpayer;
use Cheppers\SzamlazzClient\DataType\Response\TaxPayerResponse;
use Cheppers\SzamlazzClient\SzamlazzClient;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;

/**
 * @covers \Cheppers\SzamlazzClient\SzamlazzClient
 */
class SzamlazzClientTest extends TestCase
{

    public function casesGetTaxPayer()
    {
        $taxpayerBasic = new TaxPayerResponse();
        $taxpayerBasic->requestId = '170_k8knfskxnjdn24p97errf7';
        $taxpayerBasic->timestamp = '2018-07-05T10:57:46.810Z';
        $taxpayerBasic->requestVersion = '1.0';
        $taxpayerBasic->funcCode = 'OK';
        $taxpayerBasic->taxpayerValidity = true;
        $taxpayerBasic->taxpayerName = 'My Name';
        $taxpayerBasic->address = new Address();
        $taxpayerBasic->address->countryCode = 'HU';
        $taxpayerBasic->address->postalCode = '1031';
        $taxpayerBasic->address->city = 'BUDAPEST';
        $taxpayerBasic->address->streetName = 'ZÁHONY';
        $taxpayerBasic->address->publicPlaceCategory = 'UTCA';
        $taxpayerBasic->address->number = '42';

        return [
            'basic' => [
                $taxpayerBasic,
                implode('', [
                    implode(' ', [
                        '<xmltaxpayer',
                        'xmlns="http://www.szamlazz.hu/xmltaxpayer"',
                        'xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"',
                        'xsi:schemaLocation="http://www.szamlazz.hu/xmltaxpayer',
                        'http://www.szamlazz.hu/szamla/docs/xsds/taxpayer/xmltaxpayer.xsd">',
                    ]),
                    '<beallitasok>',
                    '<szamlaagentkulcs>my-api-key</szamlaagentkulcs>',
                    '</beallitasok>',
                    '<torzsszam>my-tax-payer-1</torzsszam>',
                    '</xmltaxpayer>',
                ]),
                implode(PHP_EOL, [
                    '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>',
                    implode(' ', [
                        '<QueryTaxpayerResponse',
                        'xmlns="http://schemas.nav.gov.hu/OSA/1.0/api"',
                        'xmlns:ns2="http://schemas.nav.gov.hu/OSA/1.0/data">',
                    ]),
                    '    <header>',
                    '        <requestId>170_k8knfskxnjdn24p97errf7</requestId>',
                    '        <timestamp>2018-07-05T10:57:46.810Z</timestamp>',
                    '        <requestVersion>1.0</requestVersion>',
                    '    </header>',
                    '    <result>',
                    '        <funcCode>OK</funcCode>',
                    '    </result>',
                    '    <taxpayerValidity>true</taxpayerValidity>',
                    '    <taxpayerData>',
                    '        <taxpayerName>My Name</taxpayerName>',
                    '        <taxpayerAddress>',
                    '            <countryCode>HU</countryCode>',
                    '            <postalCode>1031</postalCode>',
                    '            <city>BUDAPEST</city>',
                    '            <streetName>ZÁHONY</streetName>',
                    '            <publicPlaceCategory>UTCA</publicPlaceCategory>',
                    '            <number>42</number>',
                    '        </taxpayerAddress>',
                    '    </taxpayerData>',
                    '</QueryTaxpayerResponse>',
                ]),
                QueryTaxpayer::__set_state([
                    'settings' => [
                        'apiKey' => 'my-api-key',
                    ],
                    'taxpayerId' => 'my-tax-payer-1',
                ]),
            ],
            'not found' => [
                null,
                implode('', [
                    implode(' ', [
                        '<xmltaxpayer',
                        'xmlns="http://www.szamlazz.hu/xmltaxpayer"',
                        'xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"',
                        'xsi:schemaLocation="http://www.szamlazz.hu/xmltaxpayer',
                        'http://www.szamlazz.hu/szamla/docs/xsds/taxpayer/xmltaxpayer.xsd">',
                    ]),
                    '<beallitasok>',
                    '<szamlaagentkulcs>my-api-key</szamlaagentkulcs>',
                    '</beallitasok>',
                    '<torzsszam>my-tax-payer-2</torzsszam>',
                    '</xmltaxpayer>',
                ]),
                implode(PHP_EOL, [
                    '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>',
                    implode(' ', [
                        '<QueryTaxpayerResponse',
                        'xmlns="http://schemas.nav.gov.hu/OSA/1.0/api"',
                        'xmlns:ns2="http://schemas.nav.gov.hu/OSA/1.0/data">',
                    ]),
                    '    <header>',
                    '        <requestId>143487_dryvs4cmt8jarj4gikcmt8</requestId>',
                    '        <timestamp>2019-08-01T12:04:45.784Z</timestamp>',
                    '        <requestVersion>1.1</requestVersion>',
                    '    </header>',
                    '    <result>',
                    '        <funcCode>OK</funcCode>',
                    '    </result>',
                    '    <taxpayerValidity>false</taxpayerValidity>',
                    '</QueryTaxpayerResponse>',
                ]),
                QueryTaxpayer::__set_state([
                    'settings' => [
                        'apiKey' => 'my-api-key',
                    ],
                    'taxpayerId' => 'my-tax-payer-2',
                ]),
            ],
        ];
    }

    /**
     * @dataProvider casesGetTaxPayer
     */
    public function testGetTaxPayer(
        ?TaxPayerResponse $expectedTaxPayer,
        string $expectedRequestBody,
        string $responseBody,
        QueryTaxpayer $queryTaxpayer
    ) {
        $container = [];
        $history = Middleware::history($container);
        $mock = new MockHandler([
            new Response(
                200,
                ['Content-Type' => 'application/octet-stream'],
                $responseBody
            ),
            new RequestException(
                'Error Communicating with Server',
                new Request('GET', 'order/ios.php')
            ),
        ]);
        $handlerStack = HandlerStack::create($mock);
        $handlerStack->push($history);
        $client = new Client([
            'handler' => $handlerStack,
        ]);

        $logger = new NullLogger();
        $actual = (new SzamlazzClient($client, $logger))->getTaxpayer($queryTaxpayer);

        static::assertEquals($expectedTaxPayer, $actual);

        /** @var \GuzzleHttp\Psr7\Request $request */
        $request = $container[0]['request'];
        static::assertEquals(1, count($container));
        static::assertEquals('POST', $request->getMethod());
        static::assertStringStartsWith(
            'multipart/form-data; boundary=',
            $request->getHeader('Content-type')[0]
        );
        static::assertEquals(
            ['www.szamlazz.hu'],
            $request->getHeader('Host')
        );
        static::assertEquals(
            'https://www.szamlazz.hu/szamla/',
            (string) $request->getUri()
        );

        static::assertContains($expectedRequestBody, $request->getBody()->getContents());
    }
}
