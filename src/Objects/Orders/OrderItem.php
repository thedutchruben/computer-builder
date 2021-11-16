<?php

namespace PcBuilder\Objects\Orders;

class OrderItem
{

    private string $name;
    private int $amount;
    private float $price;

    /**
     * @param string $name
     * @param int $amount
     */
    public function __construct(string $name,int $amount)
    {
        $this->name = $name;
        $this->amount = $amount;
        $this->price = 0.00;
    }


    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    function addPrice(float $price){
        $this->price += $price;
    }

    function resetPrice(){
        $this->price = 0;
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return int
     */
    public function getAmount(): int
    {
        return $this->amount;
    }

    /**
     * @param int $amount
     */
    public function setAmount(int $amount): void
    {
        $this->amount = $amount;
    }



}