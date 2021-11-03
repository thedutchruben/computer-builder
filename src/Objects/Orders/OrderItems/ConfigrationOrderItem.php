<?php

namespace PcBuilder\Objects\Orders\OrderItems;

use PcBuilder\Objects\Orders\OrderItem;

class ConfigrationOrderItem extends OrderItem
{

    private array $components;

    /**
     * @param array $components
     */
    public function __construct(string $name,int $amount,array $components)
    {
        parent::__construct($name,$amount);
        $this->components = $components;
    }

    /**
     * @return array
     */
    public function getComponents(): array
    {
        return $this->components;
    }

    /**
     * @param array $components
     */
    public function setComponents(array $components): void
    {
        $this->components = $components;
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