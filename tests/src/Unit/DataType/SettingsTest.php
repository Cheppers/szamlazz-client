<?php

namespace Cheppers\SzamlazzClient\Tests\Unit\DataType;

use Cheppers\SzamlazzClient\DataType\Settings;
use PHPUnit\Framework\TestCase;

class SettingsTest extends BaseTestBase
{
    public function casesBuildXmlData()
    {
        $values =  [
            'apiKey'               => 'test_api_key',
            'eInvoice'             => 'true',
            'keychainPassword'     => 'key_chain_passw',
            'invoiceDownload'      => 'true',
            'invoiceDownloadCount' => 42,
            'responseVersion'      => 5,
            'aggregator'           => 'test_aggregator',
        ];

        $xml = implode(PHP_EOL, [
            '<?xml version="1.0"?>',
            '<xmlszamla></xmlszamla>',
        ]);

        $settingsBasic = Settings::__set_state($values);
        $settingsEmpty = Settings::__set_state([]);
        $basicDoc = new \DOMDocument();
        $basicDoc->loadXML($xml);

        return [
            'empty' => [
                implode(PHP_EOL, [
                    '<?xml version="1.0"?>',
                    ''
                ]),
                $settingsEmpty,
                new \DOMDocument(),
            ],
            'basic' => [
                implode(PHP_EOL, [
                    '<?xml version="1.0"?>',
                    '<xmlszamla>',
                    '  <beallitasok>',
                    '    <szamlaagentkulcs>test_api_key</szamlaagentkulcs>',
                    '    <eszamla>true</eszamla>',
                    '    <kulcstartojelszo>key_chain_passw</kulcstartojelszo>',
                    '    <szamlaLetoltes>true</szamlaLetoltes>',
                    '    <szamlaLetoltesPld>42</szamlaLetoltesPld>',
                    '    <valaszVerzio>5</valaszVerzio>',
                    '    <aggregator>test_aggregator</aggregator>',
                    '  </beallitasok>',
                    '</xmlszamla>',
                    '',
                ]),
                $settingsBasic,
                $basicDoc,
            ],
        ];
    }
}
