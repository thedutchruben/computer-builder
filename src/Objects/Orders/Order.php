<?php

namespace PcBuilder\Objects\Orders;

/**
 *
 */
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
     * The status of the order
     * @var string
     */
    private string $status;

    /**
     * The total price
     * @var float
     */
    private float $totalPrice;

    /**
     * Set if the oder has been paid
     * @var bool
     */
    private bool $paid;

    /**
     * The date when the order has been placed
     * @var string
     */
    private string $orderDate;

    /**
     * Create an order
     * @param int $id
     * @param int $customerId
     * @param array $items
     */
    public function __construct(int $id, int $customerId, array $items = [])
    {
        $this->id = $id;
        $this->customerId = $customerId;
        $this->items = $items;
    }

    /**
     * Get the id of the order
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Set the id of the order
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * Get the id of the customer that placed the order
     * @return int
     */
    public function getCustomerId(): int
    {
        return $this->customerId;
    }

    /**
     * Set the customer id that placed the order
     * @param int $customerId
     */
    public function setCustomerId(int $customerId): void
    {
        $this->customerId = $customerId;
    }

    /**
     * Get the items of the order
     * @return array
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * Set the items of the order
     * @param array $items
     */
    public function setItems(array $items): void
    {
        $this->items = $items;
    }

    /**
     * Get the status of the order
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * Set the status of the order
     * @param string $status
     */
    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    /**
     * Get the total price of the order
     * @return float
     */
    public function getTotalPrice(): float
    {
        return $this->totalPrice;
    }

    /**
     * Set the total price of the order
     * @param float $totalPrice
     */
    public function setTotalPrice(float $totalPrice): void
    {
        $this->totalPrice = $totalPrice;
    }

    /**
     * Get if the order has been paid
     * @return bool
     */
    public function isPaid(): bool
    {
        return $this->paid;
    }

    /**
     * Set if the order has been paid
     * @param bool $paid
     */
    public function setPaid(bool $paid): void
    {
        $this->paid = $paid;
    }

    /**
     * Get the order date
     * @return string
     */
    public function getOrderDate(): string
    {
        return $this->orderDate;
    }

    /**
     * Set the order date
     * @param string $orderDate
     */
    public function setOrderDate(string $orderDate): void
    {
        $this->orderDate = $orderDate;
    }


}