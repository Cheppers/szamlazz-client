<?php

namespace Cheppers\SzamlazzClient\Tests\Unit\DataType\Header;

use Cheppers\SzamlazzClient\DataType\Header\InvoiceHeader;
use Cheppers\SzamlazzClient\Tests\Unit\DataType\BaseTestBase;
use PHPUnit\Framework\TestCase;

class InvoiceHeaderTest extends BaseTestBase
{
    /**
     * {@inheritdoc}
     */
    protected $className = InvoiceHeader::class;

    /**
     * {@inheritdoc}
     */
    public function casesExportData()
    {
        return [
            'empty' => [[], []],
            'basic' => [
                [
                    'issueDate'         => '2019-01-01',
                    'fulfillmentDate'   => '2019-01-01',
                    'paymentDue'        => '2019-01-01',
                    'paymentMethod'     => 'card',
                    'currency'          => 'HUF',
                    'invoiceLanguage'   => 'hu',
                    'comment'           => 'test comment',
                    'exchangeBank'      => 'test exchange bank',
                    'exchangeRate'      => 42.5,
                    'orderNumber'       => 'on-42',
                    'proformaNumber'    => 'prfn-42',
                    'depositBill'       => false,
                    'finalBill'         => true,
                    'creditInvoice'     => false,
                    'correctiveNumber'  => 'corective number 42',
                    'prepaymentRequest' => true,
                    'deliveryNote'      => false,
                    'logoExtra'         => 'extra logo',
                    'billNumberPrefix'  => 'bill prefix',
                    'correctionToPay'   => 42.7,
                    'paid'              => true,
                    'profitVat'         => true,
                ],
                [
                    'fejlec' => [
                        'keltDatum' => '2019-01-01',
                        'teljesitesDatum' => '2019-01-01',
                        'fizetesiHataridoDatum' => '2019-01-01',
                        'fizmod' => 'card',
                        'penznem' => 'HUF',
                        'szamlaNyelve' => 'hu',
                        'megjegyzes' => 'test comment',
                        'arfolyamBank' => 'test exchange bank',
                        'arfolyam' => 42.5,
                        'rendelesSzam' => 'on-42',
                        'dijbekeroSzamlaszam' => 'prfn-42',
                        'elolegszamla' => false,
                        'vegszamla' => true,
                        'helyesbitoszamla' => false,
                        'helyesbitettSzamlaszam' => 'corective number 42',
                        'dijbekero' => true,
                        'szallitolevel' => false,
                        'logoExtra' => 'extra logo',
                        'szamlaszamElotag' => 'bill prefix',
                        'fizetendoKorrekcio' => 42.7,
                        'fizetve' => true,
                        'arresAfa' => true,
                    ],
                ],
            ]
        ];
    }
}
