<?php

namespace Cheppers\SzamlazzClient\Tests\Unit\DataType;

use Cheppers\SzamlazzClient\DataType\Buyer\InvoiceBuyer;
use Cheppers\SzamlazzClient\DataType\BuyerLedger;
use Cheppers\SzamlazzClient\DataType\GenerateInvoice;
use Cheppers\SzamlazzClient\DataType\Header\InvoiceHeader;
use Cheppers\SzamlazzClient\DataType\Item;
use Cheppers\SzamlazzClient\DataType\Seller;
use Cheppers\SzamlazzClient\DataType\Settings\InvoiceSettings;
use Cheppers\SzamlazzClient\DataType\Waybill\MPL;
use Cheppers\SzamlazzClient\DataType\Waybill\Waybill;
use Exception;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Cheppers\SzamlazzClient\DataType\GenerateInvoice
 */
class GenerateInvoiceTest extends TestCase
{
    public function casesSetState()
    {
        $basicInvoice = new GenerateInvoice();
        $settings = new InvoiceSettings();
        $header = new InvoiceHeader();
        $seller = new Seller();
        $buyer = new InvoiceBuyer();
        $wayBill = new Waybill();
        $mpl = new MPL();
        $itemOne = new Item();
        $itemTwo = new Item();
        $settings->apiKey = 'myApiKey';
        $settings->eInvoice = true;
        $settings->keychainPassword = 'myPassword';
        $settings->invoiceDownload = true;
        $settings->invoiceDownloadCount = 1;
        $settings->aggregator = 'test_aggregator_01';
        $settings->responseVersion = 2;
        $header->issueDate = '2019-01-01';
        $header->fulfillmentDate = '2019-01-02';
        $header->paymentDue = '2019-01-03';
        $header->paymentMethod = 'card';
        $header->currency = 'HUF';
        $header->invoiceLanguage = 'hu';
        $header->comment = 'comment';
        $header->exchangeBank = 'Test bank';
        $header->exchangeRate = 1.0;
        $header->orderNumber = 'order-1';
        $header->proformaNumber = 'proforma-number';
        $header->depositBill = true;
        $header->finalBill = false;
        $header->creditInvoice = false;
        $header->correctiveNumber = '123';
        $header->prepaymentRequest = false;
        $header->deliveryNote = 'note-1';
        $header->logoExtra = 'extra-logo-1';
        $header->billNumberPrefix = 'prefix-1';
        $header->correctionToPay = 2.0;
        $header->paid = true;
        $header->profitVat = true;
        $seller->bankAccountNumber = '1122334455-66-7';
        $seller->bank = 'Test bank 2';
        $seller->emailReplyTo = 'example@example.com';
        $seller->emailSubject = 'test subject';
        $seller->emailBody = 'test email, body';
        $seller->signerName = 'Signer Name';
        $buyer->id = 'Foo';
        $buyer->name = 'Bar';
        $buyer->email = 'test@test.com';
        $buyer->city = 'Budapest';
        $buyer->address = 'FooBar';
        $buyer->country = 'Hungary';
        $buyer->zip= '1122';
        $buyer->postalZip = '1123';
        $buyer->postalCountry = 'Austria';
        $buyer->postalCity = 'Postal city';
        $buyer->postalAddress = 'Postal address';
        $buyer->postalName = 'Test Postal name';
        $buyer->taxPayer = 112233;
        $buyer->taxNumberEU = '87654321';
        $buyer->taxNumber = '12345678';
        $buyer->signatoryName = 'Signatory Name';
        $buyer->sendEmail = true;
        $buyer->comment = 'test comment';
        $buyer->phoneNumber = 'phone number';
        $itemOne->title = 'Product 01';
        $itemOne->id = 'test-id';
        $itemOne->quantity = 1.0;
        $itemOne->quantityUnit = 'db';
        $itemOne->netUnitPrice = 1000;
        $itemOne->vat = 27;
        $itemOne->priceGapVatBase = 1000.0;
        $itemOne->netPrice = 1000;
        $itemOne->vatAmount = 270;
        $itemOne->grossAmount = 1270;
        $itemOne->comment = 'test item comment';
        $itemTwo->title = 'Product 02';
        $itemTwo->id = 'test-id';
        $itemTwo->quantity = 1.0;
        $itemTwo->quantityUnit = 'db';
        $itemTwo->netUnitPrice = 1000;
        $itemTwo->vat = 27;
        $itemTwo->priceGapVatBase = 1000.0;
        $itemTwo->netPrice = 1000;
        $itemTwo->vatAmount = 270;
        $itemTwo->grossAmount = 1270;
        $itemTwo->comment = 'test item comment';
        $mpl->buyerCode = 'buyer-code';
        $mpl->barcode = 'barcode';
        $mpl->weight = 'weight';
        $mpl->service = 'service';
        $mpl->insuredValue = 'insured-value';
        $wayBill->destination = 'destination';
        $wayBill->parcel = 'parcel';
        $wayBill->barcode = 'barcode';
        $wayBill->comment = 'comment';
        $wayBill->mpl = $mpl;
        $basicInvoice->settings = $settings;
        $basicInvoice->header = $header;
        $basicInvoice->seller = $seller;
        $basicInvoice->buyer = $buyer;
        $basicInvoice->items = [$itemOne, $itemTwo];
        $basicInvoice->waybill = $wayBill;

        return [
            'empty' => [
                new GenerateInvoice(),
                [],
            ],
            'basic' =>[
                $basicInvoice,
                [
                    'settings' => [
                        'apiKey'               => 'myApiKey',
                        'eInvoice'             => true,
                        'invoiceDownload'      => true,
                        'invoiceDownloadCount' => 1,
                        'responseVersion'      => 2,
                        'keychainPassword'     => 'myPassword',
                        'aggregator'           => 'test_aggregator_01',
                    ],
                    'header' => [
                        'issueDate'         => '2019-01-01',
                        'fulfillmentDate'   => '2019-01-02',
                        'paymentDue'        => '2019-01-03',
                        'paymentMethod'     => 'card',
                        'currency'          => 'HUF',
                        'invoiceLanguage'   => 'hu',
                        'comment'           => 'comment',
                        'exchangeBank'      => 'Test bank',
                        'exchangeRate'      => 1.0,
                        'orderNumber'       => 'order-1',
                        'proformaNumber'    => 'proforma-number',
                        'depositBill'       => true,
                        'finalBill'         => false,
                        'creditInvoice'     => false,
                        'correctiveNumber'  => '123',
                        'prepaymentRequest' => false,
                        'deliveryNote'      => 'note-1',
                        'logoExtra'         => 'extra-logo-1',
                        'billNumberPrefix'  => 'prefix-1',
                        'correctionToPay'   => 2.0,
                        'paid'              => true,
                        'profitVat'         => true,
                    ],
                    'seller' => [
                        'bank'              => 'Test bank 2',
                        'bankAccountNumber' => '1122334455-66-7',
                        'emailReplyTo'      => 'example@example.com',
                        'emailSubject'      => 'test subject',
                        'emailBody'         => 'test email, body',
                        'signerName'        => 'Signer Name',
                    ],
                    'buyer' => [
                        'name'          => 'Bar',
                        'country'       => 'Hungary',
                        'zip'           => '1122',
                        'city'          => 'Budapest',
                        'address'       => 'FooBar',
                        'email'         => 'test@test.com',
                        'sendEmail'     => true,
                        'taxPayer'      => 112233,
                        'taxNumber'     => '12345678',
                        'taxNumberEU'   => '87654321',
                        'postalName'    => 'Test Postal name',
                        'postalCountry' => 'Austria',
                        'postalZip'     => '1123',
                        'postalCity'    => 'Postal city',
                        'postalAddress' => 'Postal address',
                        'buyerLedger'   => null,
                        'id'            => 'Foo',
                        'signatoryName' => 'Signatory Name',
                        'phoneNumber'   => 'phone number',
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
                    ],
                    'waybill' => [
                        'destination' => 'destination',
                        'parcel'      => 'parcel',
                        'barcode'     => 'barcode',
                        'comment'     => 'comment',
                        'mpl'         => [
                            'buyerCode'    => 'buyer-code',
                            'barcode'      => 'barcode',
                            'weight'       => 'weight',
                            'service'      => 'service',
                            'insuredValue' => 'insured-value',
                        ],
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
    public function testSetState(GenerateInvoice $expected, array $data)
    {
        $instance = GenerateInvoice::__set_state($data);

        static::assertEquals($expected, $instance);
    }

    public function casesBuildXmlStringSuccess()
    {
        $basicInvoice = new GenerateInvoice();
        $settings = new InvoiceSettings();
        $header = new InvoiceHeader();
        $seller = new Seller();
        $buyer = new InvoiceBuyer();
        $buyerLedger = new BuyerLedger();
        $itemOne = new Item();
        $itemTwo = new Item();
        $settings->apiKey = 'myApiKey';
        $settings->eInvoice = true;
        $settings->keychainPassword = 'myPassword';
        $settings->invoiceDownload = true;
        $settings->invoiceDownloadCount = 42;
        $settings->aggregator = 'test_aggregator_01';
        $settings->responseVersion = 2;
        $header->issueDate = '2019-01-01';
        $header->fulfillmentDate = '2019-01-02';
        $header->paymentDue = '2019-01-03';
        $header->paymentMethod = 'card';
        $header->currency = 'HUF';
        $header->invoiceLanguage = 'hu';
        $header->comment = 'comment';
        $header->exchangeBank = 'Test bank';
        $header->exchangeRate = 42.5;
        $header->orderNumber = 'order-1';
        $header->proformaNumber = 'proforma-number';
        $header->depositBill = true;
        $header->finalBill = false;
        $header->creditInvoice = false;
        $header->correctiveNumber = '123';
        $header->prepaymentRequest = false;
        $header->deliveryNote = 'note-1';
        $header->logoExtra = 'extra-logo-1';
        $header->billNumberPrefix = 'prefix-1';
        $header->correctionToPay = 2.3;
        $header->paid = true;
        $header->profitVat = true;
        $seller->bankAccountNumber = '1122334455-66-7';
        $seller->bank = 'Test bank 2';
        $seller->emailReplyTo = 'example@example.com';
        $seller->emailSubject = 'subject';
        $seller->emailBody = 'email body';
        $seller->signerName = 'Signer Name';
        $buyerLedger->bookingDate = '2019-01-04';
        $buyerLedger->buyerId = 'identity';
        $buyerLedger->buyerLedgerNumber = 'buyer-ledger-number';
        $buyerLedger->continuousCompletion = false;
        $buyer->id = 'Foo';
        $buyer->name = 'Bar';
        $buyer->email = 'test@test.com';
        $buyer->city = 'Budapest';
        $buyer->address = 'FooBar';
        $buyer->country = 'Hungary';
        $buyer->zip= '1122';
        $buyer->postalZip = '1123';
        $buyer->postalCountry = 'Austria';
        $buyer->postalCity = 'Postal city';
        $buyer->postalAddress = 'Postal address';
        $buyer->postalName = 'Postal name';
        $buyer->buyerLedger = $buyerLedger;
        $buyer->taxPayer = 112233;
        $buyer->taxNumberEU = '87654321';
        $buyer->taxNumber = '12345678';
        $buyer->signatoryName = 'Signatory Name';
        $buyer->sendEmail = true;
        $buyer->comment = 'test comment';
        $buyer->phoneNumber = 'phone number';
        $itemOne->title = 'Product 01';
        $itemOne->id = 'test-id';
        $itemOne->quantity = 1.0;
        $itemOne->quantityUnit = 'db';
        $itemOne->netUnitPrice = 1000;
        $itemOne->vat = 27;
        $itemOne->priceGapVatBase = 1000.0;
        $itemOne->netPrice = 1000;
        $itemOne->vatAmount = 270;
        $itemOne->grossAmount = 1270;
        $itemOne->comment = 'test item comment';
        $itemTwo->title = 'Product 02';
        $itemTwo->id = 'test-id';
        $itemTwo->quantity = 1.0;
        $itemTwo->quantityUnit = 'db';
        $itemTwo->netUnitPrice = 1000;
        $itemTwo->vat = 27;
        $itemTwo->priceGapVatBase = 1000.0;
        $itemTwo->netPrice = 1000;
        $itemTwo->vatAmount = 270;
        $itemTwo->grossAmount = 1270;
        $itemTwo->comment = 'test item comment';
        $basicInvoice->settings = $settings;
        $basicInvoice->header = $header;
        $basicInvoice->seller = $seller;
        $basicInvoice->buyer = $buyer;
        $basicInvoice->items = [$itemOne, $itemTwo];


        return [
            'basic' => [
                implode('', [
                    "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n",
                    implode(' ', [
                        '<xmlszamla xmlns="http://www.szamlazz.hu/xmlszamla"',
                        'xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"',
                        'xsi:schemaLocation="http://www.szamlazz.hu/xmlszamla',
                        'http://www.szamlazz.hu/szamla/docs/xsds/agent/xmlszamla.xsd">',
                    ]),
                    '<beallitasok>',
                    '<szamlaagentkulcs>myApiKey</szamlaagentkulcs>',
                    '<eszamla>true</eszamla>',
                    '<kulcstartojelszo>myPassword</kulcstartojelszo>',
                    '<szamlaLetoltes>true</szamlaLetoltes>',
                    '<szamlaLetoltesPld>42</szamlaLetoltesPld>',
                    '<valaszVerzio>2</valaszVerzio>',
                    '<aggregator>test_aggregator_01</aggregator>',
                    '</beallitasok>',
                    '<fejlec>',
                    '<keltDatum>2019-01-01</keltDatum>',
                    '<teljesitesDatum>2019-01-02</teljesitesDatum>',
                    '<fizetesiHataridoDatum>2019-01-03</fizetesiHataridoDatum>',
                    '<fizmod>card</fizmod>',
                    '<penznem>HUF</penznem>',
                    '<szamlaNyelve>hu</szamlaNyelve>',
                    '<megjegyzes>comment</megjegyzes>',
                    '<arfolyamBank>Test bank</arfolyamBank>',
                    '<arfolyam>42.5</arfolyam>',
                    '<rendelesSzam>order-1</rendelesSzam>',
                    '<dijbekeroSzamlaszam>proforma-number</dijbekeroSzamlaszam>',
                    '<elolegszamla>true</elolegszamla>',
                    '<vegszamla>false</vegszamla>',
                    '<helyesbitoszamla>false</helyesbitoszamla>',
                    '<helyesbitettSzamlaszam>123</helyesbitettSzamlaszam>',
                    '<dijbekero>false</dijbekero>',
                    '<szallitolevel>note-1</szallitolevel>',
                    '<logoExtra>extra-logo-1</logoExtra>',
                    '<szamlaszamElotag>prefix-1</szamlaszamElotag>',
                    '<fizetendoKorrekcio>2.3</fizetendoKorrekcio>',
                    '<fizetve>true</fizetve>',
                    '<arresAfa>true</arresAfa>',
                    '</fejlec>',
                    '<elado>',
                    '<bank>Test bank 2</bank>',
                    '<bankszamlaszam>1122334455-66-7</bankszamlaszam>',
                    '<emailReplyto>example@example.com</emailReplyto>',
                    '<emailTargy>subject</emailTargy>',
                    '<emailSzoveg>email body</emailSzoveg>',
                    '<alairoNeve>Signer Name</alairoNeve>',
                    '</elado>',
                    '<vevo>',
                    '<nev>Bar</nev>',
                    '<orszag>Hungary</orszag>',
                    '<irsz>1122</irsz>',
                    '<telepules>Budapest</telepules>',
                    '<cim>FooBar</cim>',
                    '<email>test@test.com</email>',
                    '<sendEmail>true</sendEmail>',
                    '<adoalany>112233</adoalany>',
                    '<adoszam>12345678</adoszam>',
                    '<adoszamEU>87654321</adoszamEU>',
                    '<postazasiNev>Postal name</postazasiNev>',
                    '<postazasiOrszag>Austria</postazasiOrszag>',
                    '<postazasiIrsz>1123</postazasiIrsz>',
                    '<postazasiTelepules>Postal city</postazasiTelepules>',
                    '<postazasiCim>Postal address</postazasiCim>',
                    '<vevoFokonyv>',
                    '<konyvelesDatum>2019-01-04</konyvelesDatum>',
                    '<vevoAzonosito>identity</vevoAzonosito>',
                    '<vevoFokonyviSzam>buyer-ledger-number</vevoFokonyviSzam>',
                    '<folyamatosTelj>false</folyamatosTelj>',
                    '</vevoFokonyv>',
                    '<azonosito>Foo</azonosito>',
                    '<alairoNeve>Signatory Name</alairoNeve>',
                    '<telefonszam>phone number</telefonszam>',
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
                    '</tetelek>',
                    "</xmlszamla>\n",
                ]),
                $basicInvoice,
            ],
        ];
    }

    /**
     * @dataProvider casesBuildXmlStringSuccess
     */
    public function testBuildXmlStringSuccess(string $expected, GenerateInvoice $generateInvoice)
    {
        $actual = $generateInvoice->buildXmlString();

        static::assertSame($expected, $actual);
    }

    public function casesBuildXmlStringFailed()
    {
        return [
            'empty' => [
                new Exception('Missing required field'),
                new GenerateInvoice(),
            ],
        ];
    }

    /**
     * @dataProvider casesBuildXmlStringFailed
     */
    public function testBuildXmlStringFailed(\Exception $expected, GenerateInvoice $generateInvoice)
    {
        static::expectExceptionObject($expected);
        $generateInvoice->buildXmlString();
    }
}
