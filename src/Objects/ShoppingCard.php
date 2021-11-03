<?php

namespace PcBuilder\Objects;

use PcBuilder\Objects\Orders\OrderItem;

class ShoppingCard
{

    private array $items;

    public function __construct()
    {
        $this->items = [];
    }


    public function addItem(OrderItem $item){
        array_push($this->items,$item);
    }

    public function clearItems()
    {
        $this->items = [];
    }
    /**
     * @return array
     */
    public function getItems(): array
    {
        return $this->items;
    }

}