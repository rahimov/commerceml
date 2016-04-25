<?php

namespace CommerceMLParser\Model;


use CommerceMLParser\Model\Types\Price;
use CommerceMLParser\Model\Types\WarehouseStock;
use CommerceMLParser\ORM\Collection;

class Offer extends Product
{
    /** @var string */
    protected $productId;
    /** @var int Количество */
    protected $quantity;
    /** @var Collection|Price[] Цены  */
    protected $prices;
    /** @var Collection|WarehouseStock[] Склад */
    protected $warehouses;

    public function __construct(\SimpleXMLElement $xml)
    {
        parent::__construct($xml);

        $ids = explode('#', (string)$xml->Ид);
        $this->productId = reset($ids);
        $this->prices = new Collection();
        $this->warehouses = new Collection();

        if ($xml->Цены) {
            foreach ($xml->Цены->Цена as $price) {
                $this->prices->add(new Price($price));
            }
        }

        if ($xml->Склад) {
            foreach ($xml->Склад as $warehouse) {
                $this->warehouses->add(new WarehouseStock($warehouse));
            }
        }
        if($xml->Остатки) {
            foreach ($xml->Остатки->Остаток as $stock) {
                if($stock->Склад){
                    foreach ($stock->Склад as $warehouse) {
                        $this->warehouses->add(new WarehouseStock($warehouse));
                    }
                }
            }
        }
    }

    /**
     * @return string
     */
    public function getProductId()
    {
        return $this->productId;
    }

    /**
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @return Collection|Types\Price[]
     */
    public function getPrices()
    {
        return $this->prices;
    }

    /**
     * @return Collection|WarehouseStock[]
     */
    public function getWarehouses()
    {
        return $this->warehouses;
    }
}
