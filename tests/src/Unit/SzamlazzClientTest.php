<?php

declare(strict_types = 1);

namespace Cheppers\SzamlazzClient\Tests\Unit;

use Cheppers\SzamlazzClient\DataType\Address;
use Cheppers\SzamlazzClient\DataType\GenerateInvoice;
use Cheppers\SzamlazzClient\DataType\GenerateReverseInvoice;
use Cheppers\SzamlazzClient\DataType\QueryTaxpayer;
use Cheppers\SzamlazzClient\DataType\Response\InvoiceResponse;
use Cheppers\SzamlazzClient\DataType\Response\ReverseInvoiceResponse;
use Cheppers\SzamlazzClient\DataType\Response\TaxPayerResponse;
use Cheppers\SzamlazzClient\SzamlazzClient;
use Exception;
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

    public function casesGetTaxPayerSuccess()
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
     * @dataProvider casesGetTaxPayerSuccess
     */
    public function testGetTaxPayerSuccess(
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
            )
        ]);
        $handlerStack = HandlerStack::create($mock);
        $handlerStack->push($history);
        $client = new Client([
            'handler' => $handlerStack,
        ]);

        $logger = new NullLogger();
        $actual = (new SzamlazzClient($client, $logger))->getTaxpayer($queryTaxpayer);

        static::assertEquals($expectedTaxPayer, $actual);

        /** @var Request $request */
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

    public function casesGetTaxPayerFailed()
    {
        return [
            'wrong header' => [
                new Exception('Invalid response content type', 53),
                QueryTaxpayer::__set_state([
                    'settings' => [
                        'apiKey' => 'my-api-key',
                    ],
                    'taxpayerId' => 'my-tax-payer-2',
                ]),
                [
                    'Content-Type' => 'application/pdf'
                ],
                '',
            ],
            'invalid response' => [
                new Exception('Invalid response', 57),
                QueryTaxpayer::__set_state([
                    'settings' => [
                        'apiKey' => 'my-api-key',
                    ],
                    'taxpayerId' => 'my-tax-payer-2',
                ]),
                [
                    'Content-Type' => 'application/octet-stream'
                ],
                '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><wrongTagName/>',
            ],
            'response with error code' => [
                new Exception('Wrong taxpayer ID', 42),
                QueryTaxpayer::__set_state([
                    'settings' => [
                        'apiKey' => 'my-api-key',
                    ],
                    'taxpayerId' => 'my-tax-payer-2',
                ]),
                [
                    'Content-Type' => 'application/octet-stream'
                ],
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
                    '        <funcCode>ERROR</funcCode>',
                    '        <errorCode>42</errorCode>',
                    '        <message>Wrong taxpayer ID</message>',
                    '    </result>',
                    '    <taxpayerValidity>false</taxpayerValidity>',
                    '</QueryTaxpayerResponse>',
                ]),
            ],
        ];
    }

    /**
     * @dataProvider casesGetTaxPayerFailed
     */
    public function testGetTaxPayerFailed(
        Exception $expectedException,
        QueryTaxpayer $queryTaxpayer,
        array $responseHeader,
        string $responseBody
    ) {
        $container = [];
        $history = Middleware::history($container);
        $mock = new MockHandler([
            new Response(
                200,
                $responseHeader,
                $responseBody
            )
        ]);
        $handlerStack = HandlerStack::create($mock);
        $handlerStack->push($history);
        $client = new Client([
            'handler' => $handlerStack,
        ]);

        $logger = new NullLogger();

        static::expectExceptionObject($expectedException);
        (new SzamlazzClient($client, $logger))->getTaxpayer($queryTaxpayer);
    }

    public function casesGenerateInvoiceSuccess()
    {
        $invoiceResponse = new InvoiceResponse();
        $invoiceResponse->success = true;
        $invoiceResponse->invoiceNumber = 'E-CHPPR-2019-487';
        $invoiceResponse->netPrice = '2000';
        $invoiceResponse->grossAmount = '2540';
        $invoiceResponse->pdfData = 'dGVzdA==';
        $invoiceResponse->buyerAccountUrl = 'testurl.com';

        return [
            'basic' => [
                $invoiceResponse,
                implode('', [
                    implode(' ', [
                        '<xmlszamla',
                        'xmlns="http://www.szamlazz.hu/xmlszamla"',
                        'xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"',
                        'xsi:schemaLocation="http://www.szamlazz.hu/xmlszamla',
                        'http://www.szamlazz.hu/szamla/docs/xsds/agent/xmlszamla.xsd">',
                    ]),
                    '<beallitasok>',
                    '<szamlaagentkulcs>my-test-api-key</szamlaagentkulcs>',
                    '<eszamla>1</eszamla>',
                    '<szamlaLetoltes>1</szamlaLetoltes>',
                    '<szamlaLetoltesPld>1</szamlaLetoltesPld>',
                    '<valaszVerzio>2</valaszVerzio>',
                    '<aggregator>test_aggregator_01</aggregator>',
                    '</beallitasok>',
                    '<fejlec>',
                    '<keltDatum>2019-08-03</keltDatum>',
                    '<teljesitesDatum>2019-08-03</teljesitesDatum>',
                    '<fizetesiHataridoDatum>2019-08-03</fizetesiHataridoDatum>',
                    '<fizmod>card</fizmod>',
                    '<penznem>HUF</penznem>',
                    '<szamlaNyelve>hu</szamlaNyelve>',
                    '<megjegyzes>test comment</megjegyzes>',
                    '<arfolyamBank>test exchange bank</arfolyamBank>',
                    '<arfolyam>42.5</arfolyam>',
                    '<rendelesSzam>on-42</rendelesSzam>',
                    '<dijbekeroSzamlaszam>prfn-42</dijbekeroSzamlaszam>',
                    '<elolegszamla>true</elolegszamla>',
                    '<vegszamla>false</vegszamla>',
                    '<helyesbitoszamla>false</helyesbitoszamla>',
                    '<helyesbitettSzamlaszam>corrective number 42</helyesbitettSzamlaszam>',
                    '<dijbekero>false</dijbekero>',
                    '<szallitolevel>false</szallitolevel>',
                    '<logoExtra>extra logo</logoExtra>',
                    '<fizetendoKorrekcio>42.7</fizetendoKorrekcio>',
                    '<fizetve>false</fizetve>',
                    '<arresAfa>true</arresAfa>',
                    '</fejlec>',
                    '<elado>',
                    '<bank>Test Bank</bank>',
                    '<bankszamlaszam>1122334455-12-23</bankszamlaszam>',
                    '<emailReplyto>example@example.com</emailReplyto>',
                    '<emailTargy>test subject</emailTargy>',
                    '<emailSzoveg>test email, body</emailSzoveg>',
                    '</elado>',
                    '<vevo>',
                    '<nev>Test Name</nev>',
                    '<orszag>Hungary</orszag>',
                    '<irsz>1122</irsz>',
                    '<telepules>Budapest</telepules>',
                    '<cim>Fo utca 13.</cim>',
                    '<email>buyer@test.com</email>',
                    '<sendEmail>1</sendEmail>',
                    '<adoalany>1</adoalany>',
                    '<adoszam>12345678</adoszam>',
                    '<adoszamEU>87654321</adoszamEU>',
                    '<postazasiNev>Test Postal Name</postazasiNev>',
                    '<postazasiOrszag>Germany</postazasiOrszag>',
                    '<postazasiIrsz>2233445</postazasiIrsz>',
                    '<postazasiTelepules>Munchen</postazasiTelepules>',
                    '<postazasiCim>Haupt str 13.</postazasiCim>',
                    '<azonosito>testId</azonosito>',
                    '<alairoNeve>Test Master</alairoNeve>',
                    '<telefonszam>003366998855</telefonszam>',
                    '<megjegyzes>test comment</megjegyzes>',
                    '</vevo>',
                    '<tetelek>',
                    '<tetel>',
                    '<megnevezes>Product 01</megnevezes>',
                    '<azonosito>test-id</azonosito>',
                    '<mennyiseg>1</mennyiseg>',
                    '<mennyisegiEgyseg>db</mennyisegiEgyseg>',
                    '<nettoEgysegar>1000</nettoEgysegar>',
                    '<afakulcs>27</afakulcs>',
                    '<arresAfaAlap>1000</arresAfaAlap>',
                    '<nettoErtek>1000</nettoErtek>',
                    '<afaErtek>270</afaErtek>',
                    '<bruttoErtek>1270</bruttoErtek>',
                    '<megjegyzes>test item comment</megjegyzes>',
                    '</tetel>',
                    '<tetel>',
                    '<megnevezes>Product 02</megnevezes>',
                    '<azonosito>test-id-02</azonosito>',
                    '<mennyiseg>1</mennyiseg>',
                    '<mennyisegiEgyseg>db</mennyisegiEgyseg>',
                    '<nettoEgysegar>1000</nettoEgysegar>',
                    '<afakulcs>27</afakulcs>',
                    '<arresAfaAlap>1000</arresAfaAlap>',
                    '<nettoErtek>1000</nettoErtek>',
                    '<afaErtek>270</afaErtek>',
                    '<bruttoErtek>1270</bruttoErtek>',
                    '<megjegyzes>test item comment 2</megjegyzes>',
                    '</tetel>',
                    '</tetelek>',
                    '</xmlszamla>',
                ]),
                GenerateInvoice::__set_state([
                    'settings' => [
                        'apiKey'               => 'my-test-api-key',
                        'eInvoice'             => true,
                        'invoiceDownload'      => true,
                        'invoiceDownloadCount' => 1,
                        'responseVersion'      => 2,
                        'aggregator'           => 'test_aggregator_01',
                    ],
                    'header' => [
                        'issueDate'         => '2019-08-03',
                        'fulfillmentDate'   => '2019-08-03',
                        'paymentDue'        => '2019-08-03',
                        'paymentMethod'     => 'card',
                        'currency'          => 'HUF',
                        'invoiceLanguage'   => 'hu',
                        'comment'           => 'test comment',
                        'exchangeBank'      => 'test exchange bank',
                        'exchangeRate'      => 42.5,
                        'orderNumber'       => 'on-42',
                        'proformaNumber'    => 'prfn-42',
                        'depositBill'       => 'true',
                        'finalBill'         => 'false',
                        'creditInvoice'     => 'false',
                        'correctiveNumber'  => 'corrective number 42',
                        'prepaymentRequest' => 'false',
                        'deliveryNote'      => 'false',
                        'logoExtra'         => 'extra logo',
                        'billNumberPrefix'  => '',
                        'correctionToPay'   => 42.7,
                        'paid'              => 'false',
                        'profitVat'         => 'true',
                    ],
                    'seller' => [
                        'bank'              => 'Test Bank',
                        'bankAccountNumber' => '1122334455-12-23',
                        'emailReplyTo' => 'example@example.com',
                        'emailSubject' => 'test subject',
                        'emailBody' => 'test email, body',
                    ],
                    'buyer' => [
                        'name'          => 'Test Name',
                        'country'       => 'Hungary',
                        'zip'           => '1122',
                        'city'          => 'Budapest',
                        'address'       => 'Fo utca 13.',
                        'email'         => 'buyer@test.com',
                        'sendEmail'     => true,
                        'taxPayer'      => 1,
                        'taxNumber'     => '12345678',
                        'taxNumberEU'   => '87654321',
                        'postalName'    => 'Test Postal Name',
                        'postalCountry' => 'Germany',
                        'postalZip'     => '2233445',
                        'postalCity'    => 'Munchen',
                        'postalAddress' => 'Haupt str 13.',
                        'id'            => 'testId',
                        'signatoryName' => 'Test Master',
                        'phoneNumber'   => '003366998855',
                        'comment'       => 'test comment',
                    ],
                    'items' => [
                        [
                            'title'           => 'Product 01',
                            'id'              => 'test-id',
                            'quantity'        => 1.0,
                            'quantityUnit'    => 'db',
                            'netUnitPrice'    => 1000,
                            'vat'             => 27,
                            'priceGapVatBase' => 1000.0,
                            'netPrice'        => 1000,
                            'vatAmount'       => 270,
                            'grossAmount'     => 1270,
                            'comment'         => 'test item comment',
                        ],
                        [
                            'title'           => 'Product 02',
                            'id'              => 'test-id-02',
                            'quantity'        => 1.0,
                            'quantityUnit'    => 'db',
                            'netUnitPrice'    => 1000,
                            'vat'             => 27,
                            'priceGapVatBase' => 1000.0,
                            'netPrice'        => 1000,
                            'vatAmount'       => 270,
                            'grossAmount'     => 1270,
                            'comment'         => 'test item comment 2',
                        ],
                    ]
                ]),
                implode(PHP_EOL, [
                    '<?xml version="1.0" encoding="UTF-8"?>',
                    implode(' ', [
                        '<xmlszamlavalasz',
                        'xmlns="http://www.szamlazz.hu/xmlszamlavalasz"',
                        'xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">',
                        ]),
                    '  <sikeres>true</sikeres>',
                    '  <szamlaszam>E-CHPPR-2019-487</szamlaszam>',
                    '  <szamlanetto>2000</szamlanetto>',
                    '  <szamlabrutto>2540</szamlabrutto>',
                    '  <kintlevoseg>2540</kintlevoseg>',
                    '  <vevoifiokurl>testurl.com</vevoifiokurl>',
                    '  <pdf>dGVzdA==</pdf>',
                    '</xmlszamlavalasz>',
                ])
            ]
        ];
    }

    /**
     * @dataProvider casesGenerateInvoiceSuccess
     */
    public function testGenerateInvoiceSuccess(
        InvoiceResponse $expectedInvoiceResponse,
        string $expectedRequestBody,
        GenerateInvoice $inputData,
        string $responseBody
    ) {
        $container = [];
        $history = Middleware::history($container);
        $mock = new MockHandler([
            new Response(
                200,
                ['Content-Type' => 'application/octet-stream'],
                $responseBody
            )
        ]);
        $handlerStack = HandlerStack::create($mock);
        $handlerStack->push($history);
        $client = new Client([
            'handler' => $handlerStack,
        ]);

        $logger = new NullLogger();
        $actual = (new SzamlazzClient($client, $logger))->generateInvoice($inputData);

        static::assertEquals($expectedInvoiceResponse, $actual);

        /** @var Request $request */
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

    public function casesGenerateInvoiceFailed()
    {
        return [
            'wrong header' => [
                new Exception('Invalid response content type', 53),
                GenerateInvoice::__set_state([
                    'settings' => [
                        'apiKey'               => 'my-test-api-key',
                        'eInvoice'             => true,
                        'invoiceDownload'      => true,
                    ],
                    'header' => [
                        'issueDate'         => '2019-08-03',
                        'fulfillmentDate'   => '2019-08-03',
                        'paymentDue'        => '2019-08-03',
                        'paymentMethod'     => 'card',
                        'currency'          => 'HUF',
                        'invoiceLanguage'   => 'hu',
                    ],
                    'seller' => [],
                    'buyer' => [
                        'name'          => 'Test Name',
                        'zip'           => '1122',
                        'city'          => 'Budapest',
                        'address'       => 'Fo utca 13.',
                    ],
                    'items' => [
                        [
                            'title'           => 'Product 01',
                            'quantity'        => 1.0,
                            'quantityUnit'    => 'db',
                            'netUnitPrice'    => 1000,
                            'vat'             => 27,
                            'netPrice'        => 1000,
                            'vatAmount'       => 270,
                            'grossAmount'     => 1270,
                        ],
                    ]
                ]),
                [
                    'Content-Type' => 'application/pdf'
                ],
                '',
            ],
            'invalid response' => [
                new Exception('Invalid response', 57),
                GenerateInvoice::__set_state([
                    'settings' => [
                        'apiKey'               => 'my-test-api-key',
                        'eInvoice'             => true,
                        'invoiceDownload'      => true,
                    ],
                    'header' => [
                        'issueDate'         => '2019-08-03',
                        'fulfillmentDate'   => '2019-08-03',
                        'paymentDue'        => '2019-08-03',
                        'paymentMethod'     => 'card',
                        'currency'          => 'HUF',
                        'invoiceLanguage'   => 'hu',
                    ],
                    'seller' => [],
                    'buyer' => [
                        'name'          => 'Test Name',
                        'zip'           => '1122',
                        'city'          => 'Budapest',
                        'address'       => 'Fo utca 13.',
                    ],
                    'items' => [
                        [
                            'title'           => 'Product 01',
                            'quantity'        => 1.0,
                            'quantityUnit'    => 'db',
                            'netUnitPrice'    => 1000,
                            'vat'             => 27,
                            'netPrice'        => 1000,
                            'vatAmount'       => 270,
                            'grossAmount'     => 1270,
                        ],
                    ]
                ]),
                [
                    'Content-Type' => 'application/octet-stream'
                ],
                '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><wrongTagName/>',
            ],
            'response with error code' => [
                new Exception('wrong response', 3),
                GenerateInvoice::__set_state([
                    'settings' => [
                        'apiKey'               => 'my-test-api-key',
                        'eInvoice'             => true,
                        'invoiceDownload'      => true,
                    ],
                    'header' => [
                        'issueDate'         => '2019-08-03',
                        'fulfillmentDate'   => '2019-08-03',
                        'paymentDue'        => '2019-08-03',
                        'paymentMethod'     => 'card',
                        'currency'          => 'HUF',
                        'invoiceLanguage'   => 'hu',
                    ],
                    'seller' => [],
                    'buyer' => [
                        'name'          => 'Test Name',
                        'zip'           => '1122',
                        'city'          => 'Budapest',
                        'address'       => 'Fo utca 13.',
                    ],
                    'items' => [
                        [
                            'title'           => 'Product 01',
                            'quantity'        => 1.0,
                            'quantityUnit'    => 'db',
                            'netUnitPrice'    => 1000,
                            'vat'             => 27,
                            'netPrice'        => 1000,
                            'vatAmount'       => 270,
                            'grossAmount'     => 1270,
                        ],
                    ]
                ]),
                [
                    'Content-Type' => 'application/octet-stream'
                ],
                implode(PHP_EOL, [
                    '<?xml version="1.0" encoding="UTF-8"?>',
                    implode(' ', [
                        '<xmlszamlavalasz',
                        'xmlns="http://www.szamlazz.hu/xmlszamlavalasz"',
                        'xmlns:ns2="http://www.w3.org/2001/XMLSchema-instance">',
                    ]),
                    '    <sikeres>false</sikeres>',
                    '    <hibakod>3</hibakod>',
                    '    <hibauzenet>wrong response</hibauzenet>',
                    '</xmlszamlavalasz>',
                ]),
            ],
        ];
    }

    /**
     * @dataProvider casesGenerateInvoiceFailed
     */
    public function testGenerateInvoiceFailed(
        Exception $expectedException,
        GenerateInvoice $generateInvoice,
        array $responseHeader,
        string $responseBody
    ) {
        $container = [];
        $history = Middleware::history($container);
        $mock = new MockHandler([
            new Response(
                200,
                $responseHeader,
                $responseBody
            )
        ]);
        $handlerStack = HandlerStack::create($mock);
        $handlerStack->push($history);
        $client = new Client([
            'handler' => $handlerStack,
        ]);

        $logger = new NullLogger();

        static::expectExceptionObject($expectedException);
        (new SzamlazzClient($client, $logger))->generateInvoice($generateInvoice);
    }

    public function casesGenerateReverseInvoiceSuccess()
    {
        $reverseInvoiceResponse = new ReverseInvoiceResponse();
        $reverseInvoiceResponse->buyerAccountUrl = 'http://szamlazz.hu/szamla/test-url';
        $reverseInvoiceResponse->debit = -3810;
        $reverseInvoiceResponse->nettoTotal = -3000;
        $reverseInvoiceResponse->accountNumber = 'E-CHPPR-2019-447';
        $reverseInvoiceResponse->grossTotal = -3810;
        $reverseInvoiceResponse->pdfData = 'dGVzdA==';

        return [
            'basic' => [
                $reverseInvoiceResponse,
                implode('', [
                    implode(' ', [
                        '<xmlszamlast',
                        'xmlns="http://www.szamlazz.hu/xmlszamlast"',
                        'xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"',
                        'xsi:schemaLocation="http://www.szamlazz.hu/xmlszamlast',
                        'http://www.szamlazz.hu/szamla/docs/xsds/agentst/xmlszamlast.xsd">',
                    ]),
                    '<beallitasok>',
                    '<szamlaagentkulcs>my-test-api-key</szamlaagentkulcs>',
                    '<eszamla>1</eszamla>',
                    '<kulcstartojelszo>password</kulcstartojelszo>',
                    '<szamlaLetoltes>1</szamlaLetoltes>',
                    '<szamlaLetoltesPld>1</szamlaLetoltesPld>',
                    '</beallitasok>',
                    '<fejlec>',
                    '<szamlaszam>123456789</szamlaszam>',
                    '<keltDatum>2019-08-03</keltDatum>',
                    '<teljesitesDatum>2019-08-03</teljesitesDatum>',
                    '<tipus>test-type</tipus>',
                    '</fejlec>',
                    '<elado>',
                    '<emailReplyto>example@example.com</emailReplyto>',
                    '<emailTargy>test subject</emailTargy>',
                    '<emailSzoveg>test email body</emailSzoveg>',
                    '</elado>',
                    '<vevo>',
                    '<email>buyer@test.com</email>',
                    '</vevo>',
                    '</xmlszamlast>',
                ]),
                GenerateReverseInvoice::__set_state([
                    'settings' => [
                        'apiKey'               => 'my-test-api-key',
                        'eInvoice'             => true,
                        'keychainPassword'     => 'password',
                        'invoiceDownload'      => true,
                        'invoiceDownloadCount' => 1,
                    ],
                    'header' => [
                        'accountNumber'     => '123456789',
                        'issueDate'         => '2019-08-03',
                        'fulfillmentDate'   => '2019-08-03',
                        'type'              => 'test-type',
                    ],
                    'seller' => [
                        'emailReplyTo' => 'example@example.com',
                        'emailSubject' => 'test subject',
                        'emailBody'    => 'test email body',
                    ],
                    'buyer' => [
                        'email' => 'buyer@test.com',
                    ],
                ]),
                [
                    'Content-Type' => 'application/pdf',
                    'szlahu_kintlevoseg' => [-3810],
                    'szlahu_vevoifiokurl' => ['http://szamlazz.hu/szamla/test-url'],
                    'szlahu_nettovegosszeg' => [-3000],
                    'szlahu_szamlaszam' => ['E-CHPPR-2019-447'],
                    'szlahu_bruttovegosszeg' => [-3810],
                ],
                'dGVzdA==',
            ]
        ];
    }

    /**
     * @dataProvider casesGenerateReverseInvoiceSuccess
     */
    public function testGenerateReverseInvoiceSuccess(
        ReverseInvoiceResponse $expectedReverseInvoiceResponse,
        string $expectedRequestBody,
        GenerateReverseInvoice $inputData,
        array $responseHeader,
        string $responseBody
    ) {
        $container = [];
        $history = Middleware::history($container);
        $mock = new MockHandler([
            new Response(
                200,
                $responseHeader,
                $responseBody
            )
        ]);
        $handlerStack = HandlerStack::create($mock);
        $handlerStack->push($history);
        $client = new Client([
            'handler' => $handlerStack,
        ]);

        $logger = new NullLogger();
        $actual = (new SzamlazzClient($client, $logger))->generateReverseInvoice($inputData);

        static::assertEquals($expectedReverseInvoiceResponse, $actual);

        /** @var Request $request */
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

    public function casesGenerateReverseInvoiceFailed()
    {
        return [
            'wrong header' => [
                new Exception('Invalid response content type', 53),
                GenerateReverseInvoice::__set_state([
                    'settings' => [
                        'apiKey' => 'my-api-key',
                        'eInvoice' => true,
                        'invoiceDownload' => true,
                    ],
                    'header' => [
                        'accountNumber' => 'E-CHPPR-2019-446',
                    ],
                    'seller' => [
                        'emailReplyto' => 'email@email.com',
                        'emailSubject' => 'reverse invoice',
                        'emailText' => 'test txt',
                    ],
                    'buyer' => [
                        'email' => 'email@emailtest.com',
                    ],
                ]),
                [
                    'Content-Type' => 'application/json'
                ],
                '',
            ],
            'response with error code' => [
                new Exception('Wrong account number', 42),
                GenerateReverseInvoice::__set_state([
                    'settings' => [
                        'apiKey' => 'my-api-key',
                        'eInvoice' => true,
                        'invoiceDownload' => true,
                    ],
                    'header' => [
                        'accountNumber' => 'E-CHPPR-2019-446',
                    ],
                    'seller' => [
                        'emailReplyto' => 'email@email.com',
                        'emailSubject' => 'reverse invoice',
                        'emailText' => 'test txt',
                    ],
                    'buyer' => [
                        'email' => 'email@emailtest.com',
                    ],
                ]),
                [
                    'Content-Type' => 'text/html;charset=UTF-8',
                    'szlahu_error' => ['Wrong account number'],
                    'szlahu_error_code' => [42],
                ],
                '',
            ],
        ];
    }

    /**
     * @dataProvider casesGenerateReverseInvoiceFailed
     */
    public function testGenerateReverseInvoiceFailed(
        Exception $expectedException,
        GenerateReverseInvoice $generateReverseInvoice,
        array $responseHeader,
        string $responseBody
    ) {
        $container = [];
        $history = Middleware::history($container);
        $mock = new MockHandler([
            new Response(
                200,
                $responseHeader,
                $responseBody
            )
        ]);
        $handlerStack = HandlerStack::create($mock);
        $handlerStack->push($history);
        $client = new Client([
            'handler' => $handlerStack,
        ]);

        $logger = new NullLogger();

        static::expectExceptionObject($expectedException);
        (new SzamlazzClient($client, $logger))->generateReverseInvoice($generateReverseInvoice);
    }
}
