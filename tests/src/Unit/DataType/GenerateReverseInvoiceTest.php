<?php

namespace Cheppers\SzamlazzClient\Tests\Unit\DataType;

use Cheppers\SzamlazzClient\DataType\Buyer;
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
        $buyer = new Buyer();
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
}
