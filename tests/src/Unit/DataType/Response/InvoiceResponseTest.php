<?php

namespace Cheppers\SzamlazzClient\Tests\Unit\DataType\Response;

use Cheppers\SzamlazzClient\DataType\Response\InvoiceResponse;
use DOMDocument;
use DOMElement;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Cheppers\SzamlazzClient\DataType\Response\InvoiceResponse
 */
class InvoiceResponseTest extends TestCase
{
    public function casesSetState()
    {
        $invoiceResponseBasic = new InvoiceResponse();
        $invoiceResponseBasic->success = true;
        $invoiceResponseBasic->invoiceNumber = 'ABC123';
        $invoiceResponseBasic->netPrice = 1000;
        $invoiceResponseBasic->grossAmount = 2000;
        $invoiceResponseBasic->pdfData = 'base64 pdf';
        $invoiceResponseBasic->errorCode = 42;
        $invoiceResponseBasic->errorMessage = 'Foo Bar';

        $xml = implode(PHP_EOL, [
            '<?xml version="1.0" encoding="UTF-8"?>',
            implode(' ', [
                '<xmlszamlavalasz xmlns="http://www.szamlazz.hu/xmlszamlavalasz"',
                'xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">'
                ]),
            '  <sikeres>true</sikeres>',
            '  <szamlaszam>ABC123</szamlaszam>',
            '  <szamlanetto>1000</szamlanetto>',
            '  <szamlabrutto>2000</szamlabrutto>',
            '  <pdf>base64 pdf</pdf>',
            '  <hibakod>42</hibakod>',
            '  <hibauzenet>Foo Bar</hibauzenet>',
            '</xmlszamlavalasz>'
        ]);

        $doc = new DOMDocument();
        $doc->loadXML($xml);
        $elementBasic = $doc->getElementsByTagName('xmlszamlavalasz')[0];

        return [
            'empty' => [
                new InvoiceResponse(),
                new DOMElement('empty'),
            ],
            'basic' => [
                $invoiceResponseBasic,
                $elementBasic
            ]
        ];
    }

    /**
     * @dataProvider casesSetState
     */
    public function testSetState(InvoiceResponse $expected, DOMElement $element)
    {
        $instance = InvoiceResponse::__set_state($element);

        foreach ($expected as $name => $value) {
            static::assertEquals($value, $instance->{$name});
        }
    }
}
