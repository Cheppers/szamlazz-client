<?php

declare(strict_types=1);

namespace Cheppers\SzamlazzClient\DataType;

class SzamlaAgentResponse
{
    const RESULT_AS_TEXT = 1;

    const RESULT_AS_XML = 2;

    const RESULT_AS_TAXPAYER_XML = 3;

    /**
     * @var array
     */
    public $response;

    /**
     * @var int
     */
    public $httpCode;

    /**
     * @var string
     */
    public $errorMsg = '';

    /**
     * @var int
     */
    public $errorCode;

    /**
     * @var string
     */
    public $documentNumber;

    /**
     * @var \SimpleXMLElement
     */
    public $xmlData;

    /**
     * @var string
     */
    public $pdfFile;

    /**
     * @var string
     */
    public $content;

    /**
     * @var string
     */
}
