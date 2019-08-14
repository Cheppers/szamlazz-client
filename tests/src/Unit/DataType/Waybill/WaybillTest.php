<?php

namespace Cheppers\SzamlazzClient\Tests\Unit\DataType\Waybill;

use Cheppers\SzamlazzClient\DataType\Waybill\MPL;
use Cheppers\SzamlazzClient\DataType\Waybill\PPP;
use Cheppers\SzamlazzClient\DataType\Waybill\Sprinter;
use Cheppers\SzamlazzClient\DataType\Waybill\Transoflex;
use Cheppers\SzamlazzClient\DataType\Waybill\Waybill;
use Cheppers\SzamlazzClient\Tests\Unit\DataType\BaseTestBase;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Cheppers\SzamlazzClient\DataType\Waybill\Waybill<extended>
 */
class WaybillTest extends BaseTestBase
{
    /**
     * {@inheritdoc}
     */
    protected $className = Waybill::class;

    public function casesBuildXmlData()
    {
        $values = [
            'destination' => 'destination-1',
            'parcel'   => 'parcel-1',
            'barcode'  => 'barcode-1',
            'comment'  => 'comment-1',
            'tof'      => [
                'id'           => 'tof-id',
                'shippingId'   => 'tof-shipping-id',
                'packetNumber' => 42,
                'countryCode'  => 'country-code',
                'zip'          => 'zip-1',
                'service'      => 'service-1',
            ],
            'ppp'      => [
                'barcodePrefix'  => 'barcode-prefix',
                'barcodePostfix' => 'barcode-postfix',
            ],
            'sprinter' => [
                'id'             => 'sprinter-id',
                'senderId'       => 'sender-id',
                'shipmentZip'    => 'shipment-zip',
                'packetNumber'   => 43,
                'barcodePostfix' => 'barcode-postfix',
                'shippingTime'   => 'shipping-time',
            ],
            'mpl'      => [
                'buyerCode'    => 'buyer-code',
                'barcode'      => 'barcode',
                'weight'       => 'weight',
                'service'      => 'service',
                'insuredValue' => 44.5,
            ],
        ];

        $xml = implode(PHP_EOL, [
            '<?xml version="1.0"?>',
            '<xmlszamla></xmlszamla>',
        ]);

        $emptyWaybill = Waybill::__set_state([]);
        $basicWaybill = Waybill::__set_state($values);
        $basicXml = new \DOMDocument();
        $basicXml->loadXML($xml);

        return [
            'empty' => [
                implode(PHP_EOL, [
                    '<?xml version="1.0"?>',
                    '',
                ]),
                $emptyWaybill,
                new \DOMDocument(),
            ],
            'basic' => [
                implode(PHP_EOL, [
                    '<?xml version="1.0"?>',
                    '<xmlszamla>',
                    '  <fuvarlevel>',
                    '    <uticel>destination-1</uticel>',
                    '    <futarSzolgalat>parcel-1</futarSzolgalat>',
                    '    <vonalkod>barcode-1</vonalkod>',
                    '    <megjegyzes>comment-1</megjegyzes>',
                    '    <tof>',
                    '      <azonosito>tof-id</azonosito>',
                    '      <shipmentID>tof-shipping-id</shipmentID>',
                    '      <csomagszam>42</csomagszam>',
                    '      <countryCode>country-code</countryCode>',
                    '      <zip>zip-1</zip>',
                    '      <service>service-1</service>',
                    '    </tof>',
                    '    <ppp>',
                    '      <vonalkodPrefix>barcode-prefix</vonalkodPrefix>',
                    '      <vonalkodPostfix>barcode-postfix</vonalkodPostfix>',
                    '    </ppp>',
                    '    <sprinter>',
                    '      <azonosito>sprinter-id</azonosito>',
                    '      <feladokod>sender-id</feladokod>',
                    '      <iranykod>shipment-zip</iranykod>',
                    '      <csomagszam>43</csomagszam>',
                    '      <vonalkodPostfix>barcode-postfix</vonalkodPostfix>',
                    '      <szallitasiIdo>shipping-time</szallitasiIdo>',
                    '    </sprinter>',
                    '    <mpl>',
                    '      <vevokod>buyer-code</vevokod>',
                    '      <vonalkod>barcode</vonalkod>',
                    '      <tomeg>weight</tomeg>',
                    '      <kulonszolgaltatasok>service</kulonszolgaltatasok>',
                    '      <erteknyilvanitas>44.5</erteknyilvanitas>',
                    '    </mpl>',
                    '  </fuvarlevel>',
                    '</xmlszamla>',
                    '',
                ]),
                $basicWaybill,
                $basicXml,
            ],
        ];
    }

    public function casesSetState()
    {
        $waybillBasic = new Waybill();
        $tof = new Transoflex();
        $ppp = new PPP();
        $sprinter = new Sprinter();
        $mpl = new MPL();
        $tof->id = 'tof-id';
        $tof->shippingId = 'tof-shipping-id';
        $tof->packetNumber = 42;
        $tof->countryCode = 'country-code';
        $tof->zip = 'zip-1';
        $tof->service = 'service-1';
        $ppp->barcodePrefix = 'barcode-prefix';
        $ppp->barcodePostfix = 'barcode-postfix';
        $sprinter->id = 'sprinter-id';
        $sprinter->senderId = 'sender-id';
        $sprinter->shipmentZip = 'shipment-zip';
        $sprinter->packetNumber = 43;
        $sprinter->barcodePostfix = 'barcode-postfix';
        $sprinter->shippingTime = 'shipping-time';
        $mpl->buyerCode = 'buyer-code';
        $mpl->barcode = 'barcode';
        $mpl->weight = 'weight';
        $mpl->service = 'service';
        $mpl->insuredValue = 44.5;
        $waybillBasic->destination = 'destination-1';
        $waybillBasic->parcel = 'parcel-1';
        $waybillBasic->barcode = 'barcode-1';
        $waybillBasic->comment = 'comment-1';
        $waybillBasic->tof = $tof;
        $waybillBasic->ppp = $ppp;
        $waybillBasic->sprinter = $sprinter;
        $waybillBasic->mpl = $mpl;

        return [
            'empty' => [
                new Waybill(),
                [],
            ],
            'basic' => [
                $waybillBasic,
                [
                    'destination' => 'destination-1',
                    'parcel'   => 'parcel-1',
                    'barcode'  => 'barcode-1',
                    'comment'  => 'comment-1',
                    'tof' => [
                        'id'           => 'tof-id',
                        'shippingId'   => 'tof-shipping-id',
                        'packetNumber' => 42,
                        'countryCode'  => 'country-code',
                        'zip'          => 'zip-1',
                        'service'      => 'service-1',
                    ],
                    'ppp' => [
                        'barcodePrefix'  => 'barcode-prefix',
                        'barcodePostfix' => 'barcode-postfix',
                    ],
                    'sprinter' => [
                        'id'             => 'sprinter-id',
                        'senderId'       => 'sender-id',
                        'shipmentZip'    => 'shipment-zip',
                        'packetNumber'   => 43,
                        'barcodePostfix' => 'barcode-postfix',
                        'shippingTime'   => 'shipping-time',
                    ],
                    'mpl' => [
                        'buyerCode'    => 'buyer-code',
                        'barcode'      => 'barcode',
                        'weight'       => 'weight',
                        'service'      => 'service',
                        'insuredValue' => 44.5,
                    ],
                    'bad_property' => [
                        'key' => 'value',
                    ],
                ],
            ],
        ];
    }

    /**
     * @dataProvider casesSetState
     */
    public function testSetState(Waybill $expected, array $data)
    {
        $instance = Waybill::__set_state($data);

        foreach ($expected as $name => $value) {
            static::assertEquals($value, $instance->{$name});
        }
    }
}
