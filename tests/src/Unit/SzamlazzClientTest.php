<?php

declare(strict_types = 1);

namespace Cheppers\SzamlazzClient\Tests\Unit;

use Cheppers\SzamlazzClient\DataType\Address;
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
        $taxPayer = new TaxPayerResponse();
        $taxPayer->requestId = '170_k8knfskxnjdn24p97errf7';
        $taxPayer->timestamp = '2018-07-05T10:57:46.810Z';
        $taxPayer->requestVersion = '1.0';
        $taxPayer->funcCode = 'OK';
        $taxPayer->taxpayerValidity = true;
        $taxPayer->taxpayerName = 'My Name';
        $taxPayer->address = new Address();
        $taxPayer->address->countryCode = 'HU';
        $taxPayer->address->postalCode = '1031';
        $taxPayer->address->city = 'BUDAPEST';
        $taxPayer->address->streetName = 'ZÁHONY';
        $taxPayer->address->publicPlaceCategory = 'UTCA';
        $taxPayer->address->number = '42';

        return [
            'basic' => [
                $taxPayer,
                implode("\n", [
                    '<?xml version="1.0" encoding="UTF-8"?>',
                    implode(' ', [
                        '<xmltaxpayer',
                        'xmlns="https://www.szamlazz.hu/xmltaxpayer"',
                        'xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"',
                        'xsi:schemaLocation="https://www.szamlazz.hu/xmltaxpayer',
                        'https://www.szamlazz.hu/szamla/docs/xsds/taxpayer/xmltaxpayer.xsd">',
                    ]),
                    '  <beallitasok>',
                    '    <szamlaagentkulcs>my-api-key</szamlaagentkulcs>',
                    '  </beallitasok>',
                    '  <torzsszam>my-tax-payer-1</torzsszam>',
                    '</xmltaxpayer>',
                ]),
                implode("\n", [
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
                'my-api-key',
                'my-tax-payer-1',
            ],
            //'not found' => [
            //    null,
            //    implode(PHP_EOL, [
            /*        '<?xml version="1.0" encoding="UTF-8"?>',*/
            //        '<Order>',
            //        '<ERROR_CODE>5011</ERROR_CODE>',
            //        '<HASH>myHash</HASH>',
            //        '<ORDER_STATUS>NOT_FOUND</ORDER_STATUS>',
            //        '</Order>'
            //    ]),
            //    'foo',
            //],
        ];
    }

    /**
     * @dataProvider casesGetTaxPayer
     */
    public function testGetTaxPayer(
        ?TaxPayerResponse $expectedTaxPayer,
        string $expectedRequestBody,
        string $responseBody,
        string $apiKey,
        string $taxpayerId
    ) {
        $container = [];
        $history = Middleware::history($container);
        $mock = new MockHandler([
            new Response(
                200,
                ['Content-Type' => 'application/xml'],
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
        $actual = (new SzamlazzClient($client, $logger))->getTaxPayer($apiKey, $taxpayerId);

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
