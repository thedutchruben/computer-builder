<?php

namespace PcBuilder\Objects\Orders\OrderItems;

use PcBuilder\Objects\Orders\OrderItem;

/**
 * The Configuration order item is an item that contains the components of the ordered configuration
 * This class extends the OrderItem
 */
class ConfigurationOrderItem extends OrderItem
{

    /**
     * The components
     * @var array
     */
    private array $components;

    /**
     * Create the order item
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
     * Get the components of the order
     * @return array
     */
    public function getComponents(): array
    {
        return $this->components;
    }

    /**
     * Set the components of the Order Item
     * @param array $components
     */
    public function setComponents(array $components): void
    {
        $this->components = $components;
    }

}