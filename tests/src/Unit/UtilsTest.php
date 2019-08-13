<?php

namespace Cheppers\SzamlazzClient\Tests\Unit;

use Cheppers\SzamlazzClient\Utils;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Psr\Log\Test\TestLogger;

/**
 * @covers \Cheppers\SzamlazzClient\Utils
 */
class UtilsTest extends TestCase
{
    public function casesValidateTaxpayerSuccessResponse()
    {
        $validXml = implode(PHP_EOL, [
            '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>',
            implode(' ', [
                '<QueryTaxpayerResponse xmlns="http://schemas.nav.gov.hu/OSA/1.0/api"',
                'xmlns:ns2="http://schemas.nav.gov.hu/OSA/1.0/data">'
            ]),
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
        ]);

        $validDoc = new \DOMDocument();
        $validDoc->loadXML($validXml);

        $invalidXml = implode(PHP_EOL, [
            '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>',
            implode(' ', [
                '<QueryTaxpayerResponse xmlns="http://schemas.nav.gov.hu/OSA/1.0/api"',
                'xmlns:ns2="http://schemas.nav.gov.hu/OSA/1.0/data">'
            ]),
            '</QueryTaxpayerResponse>'
        ]);

        $invalidDoc = new \DOMDocument();
        $invalidDoc->loadXML($invalidXml);

        $error = new \LibXMLError();
        $error->level = 2;
        $error->code = 1872;
        $error->column = 0;
        $error->message = "The document has no document element.\n";
        $error->file = '';
        $error->line = -1;

        return [
            'valid response' => [
                [],
                $validDoc,
            ],
            'invalid response' => [
                [
                    $error,
                ],
                new \DOMDocument(),
            ],
        ];
    }

    /**
     * @dataProvider casesValidateTaxpayerSuccessResponse
     */
    public function testValidateTaxpayerSuccessResponse(array $expected, \DOMDocument $doc)
    {
        $errors = Utils::validateTaxpayerSuccessResponse($doc);

        static::assertEquals($expected, $errors);
    }

    public function casesValidateTaxpayerErrorResponse()
    {
        $validXml = implode(PHP_EOL, [
            '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>',
            implode(' ', [
                '<QueryTaxpayerResponse xmlns="http://schemas.nav.gov.hu/OSA/1.0/api"',
                'xmlns:ns2="http://schemas.nav.gov.hu/OSA/1.0/data">'
            ]),
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
            '</QueryTaxpayerResponse>'
        ]);

        $validDoc = new \DOMDocument();
        $validDoc->loadXML($validXml);

        $invalidXml = implode(PHP_EOL, [
            '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>',
            implode(' ', [
                '<QueryTaxpayerResponse xmlns="http://schemas.nav.gov.hu/OSA/1.0/api"',
                'xmlns:ns2="http://schemas.nav.gov.hu/OSA/1.0/data"/>'
            ]),
        ]);

        $invalidDoc = new \DOMDocument();
        $invalidDoc->loadXML($invalidXml);

        $error = new \LibXMLError();
        $error->level = 2;
        $error->code = 1872;
        $error->column = 0;
        $error->message = "The document has no document element.\n";
        $error->file = '';
        $error->line = -1;

        return [
            'valid response' => [
                [],
                $validDoc,
            ],
            'invalid response' => [
                [
                    $error,
                ],
                new \DOMDocument(),
            ],
        ];
    }

    /**
     * @dataProvider casesValidateTaxpayerErrorResponse
     */
    public function testValidateTaxpayerErrorResponse(array $expected, \DOMDocument $doc)
    {
        $errors = Utils::validateTaxpayerErrorResponse($doc);

        static::assertEquals($expected, $errors);
    }

    public function casesValidateInvoiceResponse()
    {
        $validXml = implode(PHP_EOL, [
            '<?xml version="1.0" encoding="UTF-8"?>',
            implode(' ', [
                '<xmlszamlavalasz xmlns="http://www.szamlazz.hu/xmlszamlavalasz"',
                'xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">'
            ]),
            '  <sikeres>true</sikeres>',
            '  <szamlaszam>ABC123</szamlaszam>',
            '  <szamlanetto>1000</szamlanetto>',
            '  <szamlabrutto>2000</szamlabrutto>',
            '  <pdf>dGVzdA==</pdf>',
            '</xmlszamlavalasz>'
        ]);

        $validDoc = new \DOMDocument();
        $validDoc->loadXML($validXml);

        $invalidXml = implode(PHP_EOL, [
            '<?xml version="1.0" encoding="UTF-8"?>',
            implode(' ', [
                '<xmlszamlavalasz xmlns="http://www.szamlazz.hu/xmlszamlavalasz"',
                'xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"/>'
            ]),
        ]);

        $invalidDoc = new \DOMDocument();
        $invalidDoc->loadXML($invalidXml);

        $error = new \LibXMLError();
        $error->level = 2;
        $error->code = 1872;
        $error->column = 0;
        $error->message = "The document has no document element.\n";
        $error->file = '';
        $error->line = -1;

        return [
            'valid response' => [
                [],
                $validDoc,
            ],
            'invalid response' => [
                [
                    $error,
                ],
                new \DOMDocument(),
            ],
        ];
    }

    /**
     * @dataProvider casesValidateInvoiceResponse
     */
    public function testValidateInvoiceResponse(array $expected, \DOMDocument $doc)
    {
        $errors = Utils::validateInvoiceResponse($doc);

        static::assertEquals($expected, $errors);
    }

    public function casesLogXmlErrors()
    {
        $logger = new TestLogger();
        $logger->error('Test message');

        $error = new \LibXMLError();
        $error->message = 'Test message';

        return [
            'basic' => [
                $logger,
                [
                    $error,
                ]
            ],
        ];
    }

    /**
     * @dataProvider casesLogXmlErrors
     */
    public function testLogXmlErrors(LoggerInterface $expected, array $errors)
    {
        $logger = new TestLogger();
        Utils::logXmlErrors($logger, $errors);

        static::assertEquals($expected, $logger);
    }
}
