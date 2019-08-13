<?php

namespace Cheppers\SzamlazzClient\Tests\Unit\DataType;

use Cheppers\SzamlazzClient\DataType\Buyer\BuyerBase;
use Cheppers\SzamlazzClient\DataType\GenerateReverseInvoice;
use Cheppers\SzamlazzClient\DataType\Header\ReverseInvoiceHeader;
use Cheppers\SzamlazzClient\DataType\Seller;
use Cheppers\SzamlazzClient\DataType\Settings\ReverseInvoiceSettings;
use PHPUnit\Framework\TestCase;

class GenerateReverseInvoiceTest extends TestCase
{

    public function casesSetState()
    {
        $basicReverseInvoice = new GenerateReverseInvoice();
        $settings = new ReverseInvoiceSettings();
        $header = new ReverseInvoiceHeader();
        $seller = new Seller();
        $buyer = new BuyerBase();
        $settings->apiKey = 'myApiKey';
        $settings->eInvoice = true;
        $settings->keychainPassword = 'myPassword';
        $settings->invoiceDownload = true;
        $settings->invoiceDownloadCount = 1;
        $header->accountNumber = 'myAccountNumber';
        $header->issueDate = '2019-01-01';
        $header->fulfillmentDate = '2019-01-02';
        $header->type = 'typ1';
        $seller->emailReplyTo = 'example@example.com';
        $seller->emailSubject = 'test subject';
        $seller->emailBody = 'test email, body';
        $buyer->email = 'test@test.com';
        $basicReverseInvoice->settings = $settings;
        $basicReverseInvoice->header = $header;
        $basicReverseInvoice->seller = $seller;
        $basicReverseInvoice->buyer = $buyer;

        return [
            'empty' => [
                new GenerateReverseInvoice(),
                [],
            ],
            'basic' =>[
                $basicReverseInvoice,
                [
                    'settings' => [
                        'apiKey' => 'myApiKey',
                        'eInvoice' => true,
                        'keychainPassword' => 'myPassword',
                        'invoiceDownload' => true,
                        'invoiceDownloadCount' => 1,
                    ],
                    'header' => [
                        'accountNumber' => 'myAccountNumber',
                        'issueDate' => '2019-01-01',
                        'fulfillmentDate' => '2019-01-02',
                        'type' => 'typ1',
                    ],
                    'seller' => [
                        'emailReplyTo' => 'example@example.com',
                        'emailSubject' => 'test subject',
                        'emailBody' => 'test email, body',
                    ],
                    'buyer' => [
                        'email' => 'test@test.com',
                    ],
                    'badProperty' => [
                        'key' => 'value',
                    ],
                ],
            ]
        ];
    }

    /**
     * @dataProvider casesSetState
     */
    public function testSetState(GenerateReverseInvoice $expected, array $data)
    {
        $instance = GenerateReverseInvoice::__set_state($data);

        static::assertEquals($expected, $instance);
    }

    public function casesBuildXmlStringSuccess()
    {
        $generateReverseInvoice = new GenerateReverseInvoice();
        $settings = new ReverseInvoiceSettings();
        $settings->apiKey = 'my-api-key';
        $settings->eInvoice = false;
        $settings->invoiceDownload = true;
        $header = new ReverseInvoiceHeader();
        $header->accountNumber = 'E-TST';
        $seller = new Seller();
        $seller->emailReplyTo = 'elado@example.com';
        $seller->emailSubject = 'subject';
        $seller->emailBody = 'Lorem ipsum';
        $buyer = new BuyerBase();
        $buyer->email = 'buyer@example.com';

        $generateReverseInvoice->settings = $settings;
        $generateReverseInvoice->header = $header;
        $generateReverseInvoice->seller = $seller;
        $generateReverseInvoice->buyer = $buyer;

        return [
            'basic' => [
                implode('', [
                    "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n",
                    implode(' ', [
                        '<xmlszamlast xmlns="http://www.szamlazz.hu/xmlszamlast"',
                        'xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"',
                        'xsi:schemaLocation="http://www.szamlazz.hu/xmlszamlast',
                        'http://www.szamlazz.hu/szamla/docs/xsds/agentst/xmlszamlast.xsd">',
                    ]),
                    '<beallitasok>',
                    '<szamlaagentkulcs>my-api-key</szamlaagentkulcs>',
                    '<eszamla>false</eszamla>',
                    '<szamlaLetoltes>true</szamlaLetoltes>',
                    '</beallitasok>',
                    '<fejlec>',
                    '<szamlaszam>E-TST</szamlaszam>',
                    '</fejlec>',
                    '<elado>',
                    '<emailReplyto>elado@example.com</emailReplyto>',
                    '<emailTargy>subject</emailTargy>',
                    '<emailSzoveg>Lorem ipsum</emailSzoveg>',
                    '</elado>',
                    '<vevo>',
                    '<email>buyer@example.com</email>',
                    '</vevo>',
                    "</xmlszamlast>\n",
                ]),
                $generateReverseInvoice,
            ],
        ];
    }

    /**
     * @dataProvider casesBuildXmlStringSuccess
     */
    public function testBuildXmlStringSuccess(string $expected, GenerateReverseInvoice $generateReverseInvoice)
    {
        $actual = $generateReverseInvoice->buildXmlString();

        static::assertSame($expected, $actual);
    }
}
