<?php

declare(strict_types = 1);

namespace Cheppers\SzamlazzClient;

use Cheppers\SzamlazzClient\DataType\GenerateInvoice;
use Cheppers\SzamlazzClient\DataType\GenerateReverseInvoice;
use Cheppers\SzamlazzClient\DataType\QueryTaxpayer;
use Cheppers\SzamlazzClient\DataType\Response\InvoiceResponse;
use Cheppers\SzamlazzClient\DataType\Response\ReverseInvoiceResponse;
use Cheppers\SzamlazzClient\DataType\Response\TaxPayerResponse;

interface SzamlazzClientInterface
{

    public function getBaseUri(): string;

    /**
     * @return $this
     */
    public function setBaseUri(string $baseUri);

    public function getTaxpayer(QueryTaxpayer $queryTaxpayer): ?TaxPayerResponse;

    public function generateInvoice(GenerateInvoice $invoice): InvoiceResponse;

    public function generateReverseInvoice(GenerateReverseInvoice $reverseInvoice): ReverseInvoiceResponse;
}
