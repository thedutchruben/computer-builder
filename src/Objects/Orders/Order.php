<?php

namespace PcBuilder\Objects\Orders;

class Order
{

    /**
     * @var int The id of the order
     */
    private int $id;
    /**
     * @var int The id of the customer
     */
    private int $customerId;
    /**
     * @var array All the items
     */
    private array $items;

    /**
     * @param int $id
     * @param int $customerId
     * @param array $items
     */
    public function __construct(int $id, int $customerId, array $items)
    {
        $this->id = $id;
        $this->customerId = $customerId;
        $this->items = $items;
    }


    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getCustomerId(): int
    {
        return $this->customerId;
    }

    /**
     * @param int $customerId
     */
    public function setCustomerId(int $customerId): void
    {
        $this->customerId = $customerId;
    }

    /**
     * @return array
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @param array $items
     */
    public function setItems(array $items): void
    {
        $this->items = $items;
    }





}