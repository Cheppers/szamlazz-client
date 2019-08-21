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
    public function testBuildXmlData(string $expected, Base $classInstance, DOMDocument $doc)
    {
        $classInstance->buildXmlData($doc->documentElement);
        $doc->formatOutput = true;
        static::assertEquals($expected, $doc->saveXML());
    }

    protected function createDocument(): DOMDocument
    {
        $xml = implode(PHP_EOL, [
            '<?xml version="1.0"?>',
            '<xmlszamla></xmlszamla>',
        ]);

        $doc = new DOMDocument();
        $doc->loadXML($xml);

        return $doc;
    }
}
