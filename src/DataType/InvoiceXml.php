<?php

declare(strict_types=1);

namespace Cheppers\SzamlazzClient\DataType;

class InvoiceXml
{

    /**
     * @var string
     */
    protected $invoiceId;

    /**
     * @var bool
     */
    public $pdf;

    public function buildXmlData(SzamlaAgentRequest $request)
    {
        return [
            'felhasznalo' => '',
            'jelszo' => '',
            'szamlaszam' => '',
            'pdf' => 'true',
        ];
    }
}
