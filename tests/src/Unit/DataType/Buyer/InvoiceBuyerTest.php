<?php

namespace Cheppers\SzamlazzClient\Tests\Unit\DataType\Buyer;

use Cheppers\SzamlazzClient\DataType\Buyer\InvoiceBuyer;
use Cheppers\SzamlazzClient\Tests\Unit\DataType\BaseTestBase;
use DOMDocument;

/**
 * @covers \Cheppers\SzamlazzClient\DataType\Buyer\InvoiceBuyer<extended>
 */
class InvoiceBuyerTest extends BaseTestBase
{

    /**
     * {@inheritdoc}
     */
    protected $className = InvoiceBuyer::class;

    /**
     * {@inheritdoc}
     */
    public function casesBuildXmlData()
    {
        $invoiceBuyerEmpty = new InvoiceBuyer();

        $invoiceBuyerBasic = new InvoiceBuyer();
        $invoiceBuyerBasic->id = 'Foo';
        $invoiceBuyerBasic->name = 'Bar';
        $invoiceBuyerBasic->email = 'test@test.com';
        $invoiceBuyerBasic->city = 'Budapest';
        $invoiceBuyerBasic->address = 'FooBar';
        $invoiceBuyerBasic->country = 'Hungary';
        $invoiceBuyerBasic->zip= 'zip';
        $invoiceBuyerBasic->postalZip = '1123';
        $invoiceBuyerBasic->postalCountry = 'Austria';
        $invoiceBuyerBasic->postalCity = 'Postal city';
        $invoiceBuyerBasic->postalAddress = 'Postal address';
        $invoiceBuyerBasic->postalName = 'Postal name';
        $invoiceBuyerBasic->taxPayer = 112233;
        $invoiceBuyerBasic->taxNumberEU = 'EU taxnumber';
        $invoiceBuyerBasic->taxNumber = 'taxnumber';
        $invoiceBuyerBasic->signatoryName = 'Signatory Name';
        $invoiceBuyerBasic->sendEmail = true;
        $invoiceBuyerBasic->comment = 'test comment';
        $invoiceBuyerBasic->phoneNumber = 'phone number';

        return [
            'empty' => [
                implode(PHP_EOL, [
                    '<?xml version="1.0"?>',
                    '<xmlszamla>',
                    '  <nev></nev>',
                    '  <irsz></irsz>',
                    '  <telepules></telepules>',
                    '  <cim></cim>',
                    '</xmlszamla>',
                    ''
                ]),
                $invoiceBuyerEmpty,
                $this->createDocument(),
            ],
            'basic' => [
                implode(PHP_EOL, [
                    '<?xml version="1.0"?>',
                    '<xmlszamla>',
                    '  <nev>Bar</nev>',
                    '  <orszag>Hungary</orszag>',
                    '  <irsz>zip</irsz>',
                    '  <telepules>Budapest</telepules>',
                    '  <cim>FooBar</cim>',
                    '  <email>test@test.com</email>',
                    '  <sendEmail>true</sendEmail>',
                    '  <adoalany>112233</adoalany>',
                    '  <adoszam>taxnumber</adoszam>',
                    '  <adoszamEU>EU taxnumber</adoszamEU>',
                    '  <postazasiNev>Postal name</postazasiNev>',
                    '  <postazasiOrszag>Austria</postazasiOrszag>',
                    '  <postazasiIrsz>1123</postazasiIrsz>',
                    '  <postazasiTelepules>Postal city</postazasiTelepules>',
                    '  <postazasiCim>Postal address</postazasiCim>',
                    '  <azonosito>Foo</azonosito>',
                    '  <alairoNeve>Signatory Name</alairoNeve>',
                    '  <telefonszam>phone number</telefonszam>',
                    '  <megjegyzes>test comment</megjegyzes>',
                    '</xmlszamla>',
                    '',
                ]),
                $invoiceBuyerBasic,
                $this->createDocument(),
            ]
        ];
    }
}
