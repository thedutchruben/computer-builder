<?php

/**
 * The component is the base of the componets such as a Cpu or a GPU
 */
class Component
{
    /**
     * @var int
     */
    private int $id;
    /**
     * @var int
     */
    private int $serialnumber;
    /**
     * @var String The title of the component
     */
    private String $title;
    /**
     * @var String The description of the component
     */
    private String $description;
    /**
     * @var float The price of the component
     */
    private float $price;

    private String $image;

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
    public function getSerialnumber(): int
    {
        return $this->serialnumber;
    }

    /**
     * @param int $serialnumber
     */
    public function setSerialnumber(int $serialnumber): void
    {
        $this->serialnumber = $serialnumber;
    }

    /**
     * @return String
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param String $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return String
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param String $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * @param float $price
     */
    public function setPrice(float $price): void
    {
        $this->price = $price;
    }

    /**
     * @param String $image
     */
    public function setImage(string $image): void
    {
        $this->image = $image;
    }

    /**
     * @return String
     */
    public function getImage(): string
    {
        return $this->image;
    }
}