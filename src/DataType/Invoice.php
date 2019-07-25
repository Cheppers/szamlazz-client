<?php

declare(strict_types=1);

namespace Cheppers\SzamlazzClient\DataType;

use Cheppers\SzamlazzClient\DataType\Header\InvoiceHeader;
use Cheppers\SzamlazzClient\SzamlazzClientException;

class Invoice extends Base
{

    /**
     * {@inheritdoc}
     */
    protected $complexTypeName = 'xmlszamla';

    /**
     * {@inheritdoc}
     */
    protected static $propertyMapping = [
        'settings' => 'beallitasok',
        'header'   => 'fejlec',
        'buyer'    => 'elado',
        'seller'   => 'vevo',
        'waybill'  => 'fuvarlevel',
        'items'    => 'tetelek',
    ];

    /**
     * {@inheritdoc}
     */
    protected $requiredFields = [
        'settings',
        'header',
        'buyer',
        'seller',
        'items',
    ];

    const INVOICE_TYPE_P_INVOICE = 1;

    const INVOICE_TYPE_E_INVOICE = 2;

    const FROM_INVOICE_NUMBER = 1;

    const FROM_ORDER_NUMBER = 2;

    const CREDIT_NOTES_LIMIT = 5;

    /**
     * @var \Cheppers\SzamlazzClient\DataType\Settings
     */
    public $settings;

    /**
     * @var \Cheppers\SzamlazzClient\DataType\Header\InvoiceHeader
     */
    public $header;

    /**
     * @var \Cheppers\SzamlazzClient\DataType\Buyer
     */
    public $buyer;

    /**
     * @var \Cheppers\SzamlazzClient\DataType\Seller
     */
    public $seller;

    /**
     * @var \Cheppers\SzamlazzClient\DataType\Waybill\Waybill
     */
    public $waybill;

    /**
     * @var \Cheppers\SzamlazzClient\DataType\Item[]
     */
    public $items = [];

    public function __construct($type = self::INVOICE_TYPE_P_INVOICE)
    {
        if (!empty($type)) {
            $this->setHeader(new InvoiceHeader($type));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function buildXmlData(SzamlaAgentRequest $request)
    {
        switch ($request->xmlName) {
            case $request::XML_SCHEMA_CREATE_INVOICE:
                $data = $this->buildFieldsData($request, [
                    'beallitasok',
                    'fejlec',
                    'elado',
                    'vevo',
                    'fuvarlevel',
                    'tetelek',
                ]);
                break;
            case $request::XML_SCHEMA_DELETE_PROFORMA:
                $data = $this->buildFieldsData($request, [
                    'beallitasok',
                    'fejlec',
                ]);
                break;
            case $request::XML_SCHEMA_CREATE_REVERSE_INVOICE:
                $data = $this->buildFieldsData($request, [
                    'beallitasok',
                    'fejlec',
                    'elado',
                    'vevo',
                ]);
                break;
            case $request::XML_SCHEMA_PAY_INVOICE:
                $data = $this->buildFieldsData($request, ['beallitasok']);
                $data = array_merge($data, $this->buildXmlItemsData($this->creditNotes, 'note'));
                break;
            case $request::XML_SCHEMA_REQUEST_INVOICE_PDF:
                $settings = $this->buildFieldsData($request, ['beallitasok']);
                $data = $settings['beallitasok'];
                break;
            default:
                throw new SzamlazzClientException(
                    SzamlazzClientException::XML_SCHEMA_TYPE_NOT_EXISTS
                    . ": {$request->getXmlName()}."
                );
        }

        return $data;
    }

    private function buildFieldsData(SzamlaAgentRequest $request, array $fields)
    {
        $data = [];

        foreach ($fields as $key) {
            switch ($key) {
                case 'beallitasok':
                    $value = $request->getAgent()->getSetting()->buildXmlData($request);
                    break;
                case 'fejlec':
                    $value = $this->header->buildXmlData($request);
                    break;
                case 'tetelek':
                    $value = $this->buildXmlItemsData($this->items, 'item');
                    break;
                case 'elado':
                    $value = $this->seller ? $this->seller->buildXmlData($request) : [];
                    break;
                case 'vevo':
                    $value = $this->buyer ? $this->buyer->buildXmlData($request) : [];
                    break;
                case 'fuvarlevel':
                    $value = $this->waybill ? $this->waybill->buildXmlData($request) : [];
                    break;
                default:
                    throw new SzamlazzClientException(SzamlazzClientException::XML_KEY_NOT_EXISTS . ": {$key}");
            }

            if (isset($value)) {
                $data[$key] = $value;
            }
        }

        return $data;
    }

    protected function buildXmlItemsData(array $items, string $dataKey)
    {
        $data = [];

        foreach ($items as $key => $item) {
            $data[$dataKey . $item] = $item->buildXmlData();
        }

        return $data;
    }
}
