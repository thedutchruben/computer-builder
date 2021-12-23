<?php

namespace PcBuilder\Objects;

use PcBuilder\Objects\Orders\OrderItem;

/**
 * The shopping card is an object that wil be saved in the session data to remember what the customer wands to order
 *
 * @version 1.0
 * @author Ruben de Roos
 */
class ShoppingCart
{

    /**
     * @var array The array with the card items
     */
    private array $items;

    /**
     * Create an empty shopping card
     */
    public function __construct()
    {
        $this->items = [];
    }

    /**
     * Add an item to the shopping card
     * The item has te be an OrderItem
     * @param OrderItem $item The shopping card item
     */
    public function addItem(OrderItem $item){
        array_push($this->items,$item);
    }

    /**
     * Clear the shopping card
     */
    public function clearItems()
    {
        $this->items = [];
    }
    /**
     * Get the shopping card items
     * @return array an array with {@link OrderItem} item's
     */
    public function getItems(): array
    {
        return $this->items;
    }

}