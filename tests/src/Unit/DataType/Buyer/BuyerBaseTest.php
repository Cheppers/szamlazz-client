<?php

namespace Cheppers\SzamlazzClient\Tests\Unit\DataType\Buyer;

use Cheppers\SzamlazzClient\DataType\Buyer\BuyerBase;
use Cheppers\SzamlazzClient\Tests\Unit\DataType\BaseTestBase;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Cheppers\SzamlazzClient\DataType\Buyer\BuyerBase
 */
class BuyerBaseTest extends BaseTestBase
{
    protected $className = BuyerBase::class;

    public function casesBuildXmlData()
    {
        $buyerBaseEmpty = new BuyerBase();
        $xml = implode(PHP_EOL, [
            '<?xml version="1.0"?>',
            '<xmlszamla></xmlszamla>',
        ]);

        $basicDoc = new \DOMDocument();
        $basicDoc->loadXML($xml);

        $buyerBaseBasic = new BuyerBase();
        $buyerBaseBasic->email = 'test@test.com';

        return [
            'empty' => [
                implode(PHP_EOL, [
                    '<?xml version="1.0"?>',
                    ''
                ]),
                $buyerBaseEmpty,
                new \DOMDocument(),
            ],
            'basic' => [
                implode(PHP_EOL, [
                    '<?xml version="1.0"?>',
                    '<xmlszamla>',
                    '  <vevo>',
                    '    <email>test@test.com</email>',
                    '  </vevo>',
                    '</xmlszamla>',
                    '',
                ]),
                $buyerBaseBasic,
                $basicDoc,
            ]
        ];
    }
}
