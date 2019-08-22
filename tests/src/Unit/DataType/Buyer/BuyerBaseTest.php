<?php

namespace Cheppers\SzamlazzClient\Tests\Unit\DataType\Buyer;

use Cheppers\SzamlazzClient\DataType\Buyer\BuyerBase;
use Cheppers\SzamlazzClient\Tests\Unit\DataType\BaseTestBase;
use DOMDocument;

/**
 * @covers \Cheppers\SzamlazzClient\DataType\Buyer\BuyerBase<extended>
 */
class BuyerBaseTest extends BaseTestBase
{
    protected $className = BuyerBase::class;

    public function casesBuildXmlData()
    {
        $buyerBaseEmpty = new BuyerBase();

        $buyerBaseBasic = new BuyerBase();
        $buyerBaseBasic->email = 'test@test.com';

        return [
            'empty' => [
                implode(PHP_EOL, [
                    '<?xml version="1.0"?>',
                    '<xmlszamla>',
                    '  <email></email>',
                    '</xmlszamla>',
                    ''
                ]),
                $buyerBaseEmpty,
                $this->createDocument(),
            ],
            'basic' => [
                implode(PHP_EOL, [
                    '<?xml version="1.0"?>',
                    '<xmlszamla>',
                    '  <email>test@test.com</email>',
                    '</xmlszamla>',
                    '',
                ]),
                $buyerBaseBasic,
                $this->createDocument(),
            ]
        ];
    }
}
