<?php

namespace PcBuilder\Objects\Orders;

/**
 * The order item is an item in the order this can be customized by classes that include this class
 */
class OrderItem
{

    /**
     * The name of the order item
     * @var string
     */
    private string $name;
    /**
     * The amount of the order item
     * @var int
     */
    private int $amount;
    /**
     * The price of the order item
     * @var float
     */
    private float $price;

    /**
     * Create the order item
     * @param string $name
     * @param int $amount
     */
    public function __construct(string $name,int $amount = 1)
    {
        $this->name = $name;
        $this->amount = $amount;
        $this->price = 0.00;
    }


    /**
     * Get the name of the order item
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * add an price amount to the order item
     * @param float $price
     * @return void
     */
    function addPrice(float $price){
        $this->price += $price;
    }

    /**
     * Reset the price of the order item
     * @return void
     */
    function resetPrice(){
        $this->price = 0;
    }

    /**
     * Get the price of the order item
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * Set the name of the order item
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * Get the amount of the order item
     * @return int
     */
    public function getAmount(): int
    {
        return $this->amount;
    }

    /**
     * Set the amount of the order item
     * @param int $amount
     */
    public function setAmount(int $amount): void
    {
        $this->amount = $amount;
    }

}