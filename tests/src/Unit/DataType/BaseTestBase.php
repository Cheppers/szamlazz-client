<?php

namespace Cheppers\SzamlazzClient\Tests\Unit\DataType;

use Cheppers\SzamlazzClient\DataType\Base;
use PHPUnit\Framework\TestCase;

abstract class BaseTestBase extends TestCase
{
    /**
     * @var string|Base
     */
    protected $className = '';

    abstract public function casesExportData();

    /**
     * @dataProvider casesExportData
     */
    public function testExportData(array $expected, array $values)
    {
        $data = $this->className::__set_state($values);
        static::assertSame($expected, $data->exportData());
    }
}
