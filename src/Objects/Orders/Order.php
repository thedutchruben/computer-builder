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

    private string $status;

    private float $totalPrice;

    private bool $paid;

    private string $orderDate;

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

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    /**
     * @return float
     */
    public function getTotalPrice(): float
    {
        return $this->totalPrice;
    }

    /**
     * @param float $totalPrice
     */
    public function setTotalPrice(float $totalPrice): void
    {
        $this->totalPrice = $totalPrice;
    }

    /**
     * @return bool
     */
    public function isPaid(): bool
    {
        return $this->paid;
    }

    /**
     * @param bool $paid
     */
    public function setPaid(bool $paid): void
    {
        $this->paid = $paid;
    }

    /**
     * @return string
     */
    public function getOrderDate(): string
    {
        return $this->orderDate;
    }

    /**
     * @param string $orderDate
     */
    public function setOrderDate(string $orderDate): void
    {
        $this->orderDate = $orderDate;
    }


}