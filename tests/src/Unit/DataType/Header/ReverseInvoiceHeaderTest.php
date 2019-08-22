<?php

namespace Cheppers\SzamlazzClient\Tests\Unit\DataType\Header;

use Cheppers\SzamlazzClient\DataType\Header\ReverseInvoiceHeader;
use Cheppers\SzamlazzClient\Tests\Unit\DataType\BaseTestBase;
use DOMDocument;

/**
 * @covers \Cheppers\SzamlazzClient\DataType\Header\ReverseInvoiceHeader<extended>
 */
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

        $emptyHeader = ReverseInvoiceHeader::__set_state([]);
        $basicHeader = ReverseInvoiceHeader::__set_state($values);

        return [
            'empty' => [
                implode(PHP_EOL, [
                    '<?xml version="1.0"?>',
                    '<xmlszamla>',
                    '  <szamlaszam></szamlaszam>',
                    '</xmlszamla>',
                    '',
                ]),
                $emptyHeader,
                $this->createDocument(),
            ],
            'basic' => [
                implode(PHP_EOL, [
                    '<?xml version="1.0"?>',
                    '<xmlszamla>',
                    '  <szamlaszam>test-account-01</szamlaszam>',
                    '  <keltDatum>2019-01-01</keltDatum>',
                    '  <teljesitesDatum>2019-01-02</teljesitesDatum>',
                    '  <tipus>42</tipus>',
                    '</xmlszamla>',
                    '',
                ]),
                $basicHeader,
                $this->createDocument(),
            ],
        ];
    }
}
