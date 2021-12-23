<?php

namespace PcBuilder\Objects\Orders\OrderItems;

use PcBuilder\Objects\Orders\OrderItem;

class ConfigrationOrderItem extends OrderItem
{

    private array $components;

    /**
     * @param string $name
     * @param int $amount
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

}