<?php

namespace Cheppers\SzamlazzClient\Tests\Unit\DataType\Settings;

use Cheppers\SzamlazzClient\DataType\Settings\InvoiceSettings;
use Cheppers\SzamlazzClient\Tests\Unit\DataType\BaseTestBase;
use DOMDocument;

/**
 * @covers \Cheppers\SzamlazzClient\DataType\Settings\InvoiceSettings<extended>
 */
class InvoiceSettingsTest extends BaseTestBase
{
    public function casesBuildXmlData()
    {
        $values =  [
            'apiKey'               => 'test_api_key',
            'eInvoice'             => true,
            'keychainPassword'     => 'key_chain_passw',
            'invoiceDownload'      => true,
            'invoiceDownloadCount' => 42,
            'responseVersion'      => 5,
            'aggregator'           => 'test_aggregator',
        ];

        $settingsBasic = InvoiceSettings::__set_state($values);
        $settingsEmpty = InvoiceSettings::__set_state([]);

        return [
            'empty' => [
                implode(PHP_EOL, [
                    '<?xml version="1.0"?>',
                    '<xmlszamla>',
                    '  <szamlaagentkulcs></szamlaagentkulcs>',
                    '  <eszamla></eszamla>',
                    '  <szamlaLetoltes></szamlaLetoltes>',
                    '</xmlszamla>',
                    ''
                ]),
                $settingsEmpty,
                $this->createDocument(),
            ],
            'basic' => [
                implode(PHP_EOL, [
                    '<?xml version="1.0"?>',
                    '<xmlszamla>',
                    '  <szamlaagentkulcs>test_api_key</szamlaagentkulcs>',
                    '  <eszamla>true</eszamla>',
                    '  <kulcstartojelszo>key_chain_passw</kulcstartojelszo>',
                    '  <szamlaLetoltes>true</szamlaLetoltes>',
                    '  <szamlaLetoltesPld>42</szamlaLetoltesPld>',
                    '  <valaszVerzio>5</valaszVerzio>',
                    '  <aggregator>test_aggregator</aggregator>',
                    '</xmlszamla>',
                    '',
                ]),
                $settingsBasic,
                $this->createDocument(),
            ],
        ];
    }
}
