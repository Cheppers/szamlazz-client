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
        return static::validateDocumentBySchema($doc, 'invoiceApi');
    }

    public static function validateTaxpayerErrorResponse(DOMDocument $doc): array
    {
        return static::validateDocumentBySchema($doc, 'invoiceApiError');
    }

    public static function validateInvoiceResponse(DOMDocument $doc): array
    {
        return static::validateDocumentBySchema($doc, 'xmlszamlavalasz');
    }

    /**
     * @param \DOMDocument $doc
     * @param string[] $schemaNames
     *
     * @return \LibXMLError
     */
    public static function validateDocumentBySchemaNames(DOMDocument $doc, array $schemaNames): array
    {
        $allErrors = [];
        foreach ($schemaNames as $schemaName) {
            $errors = static::validateDocumentBySchema($doc, $schemaName);
            if (!$errors) {
                return $errors;
            }
            
            $allErrors[] = $errors;
        }

        return $allErrors;
    }

    public static function validateDocumentBySchema(DOMDocument $doc, string $schemaName): array
    {
        libxml_use_internal_errors(true);
        if ($doc->schemaValidate(static::pathToXsd($schemaName))) {
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

    public static function pathToXsd(string $schemaName): string
    {
        return static::getProjectRoot() . "/schemas/$schemaName.xsd";
    }
}
