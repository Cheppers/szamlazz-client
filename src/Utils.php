<?php

declare(strict_types = 1);

namespace Cheppers\SzamlazzClient;

use DOMDocument;
use LibXMLError;
use Psr\Log\LoggerInterface;

class Utils
{
    public static function getProjectRoot(): string
    {
        return dirname(__DIR__);
    }

    public static function validateTaxpayerSuccessResponse(DOMDocument $doc): array
    {
        $root = static::getProjectRoot();

        return static::validateDocument($doc, "$root/schemas/invoiceApi.xsd");
    }

    public static function validateTaxpayerErrorResponse(DOMDocument $doc): array
    {
        $root = static::getProjectRoot();

        return static::validateDocument($doc, "$root/schemas/invoiceApiError.xsd");
    }

    public static function validateInvoiceResponse(DOMDocument $doc): array
    {
        $root = static::getProjectRoot();

        return static::validateDocument($doc, "$root/schemas/xmlszamlavalasz.xsd");
    }

    public static function validateDocument(DOMDocument $doc, string $pathToXsd): array
    {
        libxml_use_internal_errors(true);
        if ($doc->schemaValidate($pathToXsd)) {
            return [];
        }

        $errors = libxml_get_errors();
        libxml_clear_errors();

        return $errors;
    }

    /**
     * @param LoggerInterface $logger
     * @param LibXMLError[] $errors
     */
    public static function logXmlErrors(LoggerInterface $logger, array $errors)
    {
        foreach ($errors as $error) {
            $logger->error($error->message);
        }
    }
}
