<?php

namespace Cheppers\SzamlazzClient\Tests\Unit\DataType;

use Cheppers\SzamlazzClient\DataType\Base;
use DOMDocument;
use PHPUnit\Framework\TestCase;

abstract class BaseTestBase extends TestCase
{
    /**
     * @var string|Base
     */
    protected $className = '';

    abstract public function casesBuildXmlData();

    /**
     * @dataProvider casesBuildXmlData
     */
    public function testBuildXmlData(string $expected, $classInstance, DOMDocument $doc)
    {
        /** @var Base $classInstance */
        $data = $classInstance->buildXmlData($doc);
        $data->formatOutput = true;
        $dataString = $data->saveXML();
        static::assertEquals($expected, $dataString);
    }
}
