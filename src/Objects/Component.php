<?php

namespace PcBuilder\Objects;

class Component
{
    private int $id;
    private String $displayName;
    private String $description = "<p style='color: red'>No description found</p>";
    private String $image = "https://upload.wikimedia.org/wikipedia/commons/thumb/6/65/No-Image-Placeholder.svg/832px-No-Image-Placeholder.svg.png";
    private float $price = 9999;
    private int $powerNeed;
    private String $type;

    /**
     * @param int $id
     * @param String $displayName
     */
    public function __construct(int $id, string $displayName)
    {
        $this->id = $id;
        $this->displayName = $displayName;
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
     * @param String $displayName
     */
    public function setDisplayName(string $displayName): void
    {
        $this->displayName = $displayName;
    }

    /**
     * @return String
     */
    public function getDisplayName(): string
    {
        return $this->displayName;
    }

    /**
     * @return String
     */
    public function getImage(): string
    {
        return $this->image;
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * @param String $image
     */
    public function setImage(string $image): void
    {
        $this->image = $image;
    }

    /**
     * @param float $price
     */
    public function setPrice(float $price): void
    {
        $this->price = $price;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return int
     */
    public function getPowerNeed(): int
    {
        return $this->powerNeed;
    }

    /**
     * @param int $powerNeed
     */
    public function setPowerNeed(int $powerNeed): void
    {
        $this->powerNeed = $powerNeed;
    }

    /**
     * @return String
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param String $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }
}




