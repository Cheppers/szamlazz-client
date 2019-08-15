<?php

namespace Cheppers\SzamlazzClient\Tests\Unit\DataType\Header;

use Cheppers\SzamlazzClient\DataType\Header\InvoiceHeader;
use Cheppers\SzamlazzClient\Tests\Unit\DataType\BaseTestBase;
use DOMDocument;

/**
 * @covers \Cheppers\SzamlazzClient\DataType\Header\InvoiceHeader<extended>
 */
class InvoiceHeaderTest extends BaseTestBase
{
    /**
     * {@inheritdoc}
     */
    protected $className = InvoiceHeader::class;

    public function casesBuildXmlData()
    {
        $values = [
            'issueDate'         => '2019-01-01',
            'fulfillmentDate'   => '2019-01-01',
            'paymentDue'        => '2019-01-01',
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
            'creditInvoice'     => 'true',
            'correctiveNumber'  => 'corective number 42',
            'prepaymentRequest' => 'false',
            'deliveryNote'      => 'true',
            'logoExtra'         => 'extra logo',
            'billNumberPrefix'  => 'bill prefix',
            'correctionToPay'   => 42.7,
            'paid'              => 'false',
            'profitVat'         => 'true',
        ];

        $xml = implode(PHP_EOL, [
            '<?xml version="1.0"?>',
            '<xmlszamla></xmlszamla>',
        ]);

        $emptyHeader = InvoiceHeader::__set_state([]);
        $basicHeader = InvoiceHeader::__set_state($values);
        $basicXml = new DOMDocument();
        $basicXml->loadXML($xml);

        return [
            'empty' => [
                implode(PHP_EOL, [
                    '<?xml version="1.0"?>',
                    '',
                ]),
                $emptyHeader,
                new DOMDocument(),
            ],
            'basic' => [
                implode(PHP_EOL, [
                    '<?xml version="1.0"?>',
                    '<xmlszamla>',
                    '  <fejlec>',
                    '    <keltDatum>2019-01-01</keltDatum>',
                    '    <teljesitesDatum>2019-01-01</teljesitesDatum>',
                    '    <fizetesiHataridoDatum>2019-01-01</fizetesiHataridoDatum>',
                    '    <fizmod>card</fizmod>',
                    '    <penznem>HUF</penznem>',
                    '    <szamlaNyelve>hu</szamlaNyelve>',
                    '    <megjegyzes>test comment</megjegyzes>',
                    '    <arfolyamBank>test exchange bank</arfolyamBank>',
                    '    <arfolyam>42.5</arfolyam>',
                    '    <rendelesSzam>on-42</rendelesSzam>',
                    '    <dijbekeroSzamlaszam>prfn-42</dijbekeroSzamlaszam>',
                    '    <elolegszamla>true</elolegszamla>',
                    '    <vegszamla>false</vegszamla>',
                    '    <helyesbitoszamla>true</helyesbitoszamla>',
                    '    <helyesbitettSzamlaszam>corective number 42</helyesbitettSzamlaszam>',
                    '    <dijbekero>false</dijbekero>',
                    '    <szallitolevel>true</szallitolevel>',
                    '    <logoExtra>extra logo</logoExtra>',
                    '    <szamlaszamElotag>bill prefix</szamlaszamElotag>',
                    '    <fizetendoKorrekcio>42.7</fizetendoKorrekcio>',
                    '    <fizetve>false</fizetve>',
                    '    <arresAfa>true</arresAfa>',
                    '  </fejlec>',
                    '</xmlszamla>',
                    '',
                ]),
                $basicHeader,
                $basicXml,
            ],
        ];
    }
}
