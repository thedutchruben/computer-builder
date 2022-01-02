<?php

namespace PcBuilder\Objects;

use PcBuilder\Objects\Orders\OrderItem;

/**
 * The shopping cart is an object that wil be saved in the session data to remember what the customer wands to order
 *
 * @version 1.0
 * @author Ruben de Roos
 */
class ShoppingCart
{

    /**
     * @var array The array with the cart items
     */
    private array $items;

    /**
     * Create an empty shopping cart
     */
    public function __construct()
    {
        $this->items = [];
    }

    /**
     * Add an item to the shopping cart
     * The item has te be an OrderItem
     * @param OrderItem $item The shopping cart item
     */
    public function addItem(OrderItem $item){
        array_push($this->items,$item);
    }

    /**
     * Clear the shopping cart
     */
    public function clearItems()
    {
        $this->items = [];
    }
    /**
     * Get the shopping cart items
     * @return array an array with {@link OrderItem} item's
     */
    public function getItems(): array
    {
        return $this->items;
    }

}