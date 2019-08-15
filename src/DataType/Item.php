<?php

declare(strict_types=1);

namespace Cheppers\SzamlazzClient\DataType;

class Item extends Base
{
    /**
     * {@inheritdoc}
     */
    protected static $propertyMapping = [
        'title'           => 'megnevezes',
        'id'              => 'azonosito',
        'quantity'        => 'mennyiseg',
        'quantityUnit'    => 'mennyisegiEgyseg',
        'netUnitPrice'    => 'nettoEgysegar',
        'vat'             => 'afakulcs',
        'priceGapVatBase' => 'arresAfaAlap',
        'netPrice'        => 'nettoErtek',
        'vatAmount'       => 'afaErtek',
        'grossAmount'     => 'bruttoErtek',
        'comment'         => 'megjegyzes',
        'itemLedger'      => 'tetelFokonyv',
    ];

    /**
     * @var string
     */
    public $title;

    /**
     * @var string
     */
    public $id;

    /**
     * @var double
     */
    public $quantity;

    /**
     * @var string
     */
    public $quantityUnit;

    /**
     * @var double
     */
    public $netUnitPrice;

    /**
     * @var string
     */
    public $vat;

    /**
     * @var double
     */
    public $priceGapVatBase;

    /**
     * @var double
     */
    public $netPrice;

    /**
     * @var double
     */
    public $vatAmount;

    /**
     * @var double
     */
    public $grossAmount;

    /**
     * @var string
     */
    public $comment;

    /**
     * @var ItemLedger
     */
    public $itemLedger;

    /**
     * {@inheritdoc}
     */
    protected $complexTypeName = 'tetel';

    /**
     * {@inheritdoc}
     */
    protected $requiredFields = [
        'title',
        'quantity',
        'quantityUnit',
        'netUnitPrice',
        'vat',
        'netPrice',
        'vatAmount',
        'grossAmount',
    ];
}
