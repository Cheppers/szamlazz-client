<?php

declare(strict_types=1);

namespace Cheppers\SzamlazzClient\Utils;

use Cheppers\SzamlazzClient\SzamlazzClient;

class SzamlaAgentUtil
{
    public static function checkValidXml(\SimpleXMLElement $xmlContent): array
    {
        libxml_use_internal_errors(true);

        $doc = new \DOMDocument('1.0', 'utf-8');
        $doc->loadXML($xmlContent->asXML());

        $result = libxml_get_errors();
        libxml_clear_errors();

        return $result;
    }

    public static function formatXml(\SimpleXMLElement $simpleXMLElement)
    {
        $xmlDocument = new \DOMDocument('1.0');
        $xmlDocument->preserveWhiteSpace = false;
        $xmlDocument->formatOutput = true;
        $xmlDocument->loadXML($simpleXMLElement->asXML());
        return $xmlDocument;
    }

    public static function getXmlFileName(string $prefix, string $name, object $entity = null)
    {
        if (!empty($name) && !empty($entity)) {
            $name .= '-' . (new \ReflectionClass($entity))->getShortName();
        }

        $fileName  = $prefix . '-' . mb_strtolower($name) . '-' . date('YmdHis') . '.xml';
        return self::getAbsPath(SzamlazzClient::XML_FILE_SAVE_PATH, $fileName);
    }

    /**
     * @return bool|string
     */
    public static function getRealPath(string $path)
    {
        if (!file_exists($path)) {
            return $path;
        }

        return realpath($path);
    }

    /**
     * @return bool|string
     */
    public static function getAbsPath(string $dir, string $fileName = '')
    {
        $file = static::getBasePath() . DIRECTORY_SEPARATOR . $dir . DIRECTORY_SEPARATOR . $fileName;
        return static::getRealPath($file);
    }

    /**
     * @return bool|string
     */
    public static function getBasePath()
    {
        return static::getRealPath(
            __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR
        );
    }
}
