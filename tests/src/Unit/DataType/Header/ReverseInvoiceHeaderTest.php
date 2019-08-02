<?php

namespace Cheppers\SzamlazzClient\Tests\Unit\DataType\Header;

use Cheppers\SzamlazzClient\DataType\Header\ReverseInvoiceHeader;
use Cheppers\SzamlazzClient\Tests\Unit\DataType\BaseTestBase;
use PHPUnit\Framework\TestCase;

class ReverseInvoiceHeaderTest extends BaseTestBase
{
    /**
     * {@inheritdoc}
     */
    protected $className = ReverseInvoiceHeader::class;

    public function casesBuildXmlData()
    {
        $values = [
            'accountNumber'     => 'test-account-01',
            'issueDate'         => '2019-01-01',
            'fulfillmentDate'   => '2019-01-02',
            'type'              => '42'
        ];

        $xml = implode(PHP_EOL, [
            '<?xml version="1.0"?>',
            '<xmlszamla></xmlszamla>',
        ]);

        $emptyHeader = ReverseInvoiceHeader::__set_state([]);
        $basicHeader = ReverseInvoiceHeader::__set_state($values);
        $basicXml = new \DOMDocument();
        $basicXml->loadXML($xml);

        return [
            'empty' => [
                implode(PHP_EOL, [
                    '<?xml version="1.0"?>',
                    '',
                ]),
                $emptyHeader,
                new \DOMDocument(),
            ],
            'basic' => [
                implode(PHP_EOL, [
                    '<?xml version="1.0"?>',
                    '<xmlszamla>',
                    '  <fejlec>',
                    '    <szamlaszam>test-account-01</szamlaszam>',
                    '    <keltDatum>2019-01-01</keltDatum>',
                    '    <teljesitesDatum>2019-01-02</teljesitesDatum>',
                    '    <tipus>42</tipus>',
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
