<?php

namespace PcBuilder\Objects;

class Configurator
{

    private int $id;
    private String $name;
    private String $description;
    private array $cases= [];
    private array $cpu = [];
    private array $motherboard= [];
    private array $gpu= [];
    private array $memory= [];
    private array $psu= [];
    private array $storage= [];
    private array $rgb= [];
    private array $dvd= [];
    private array $os= [];

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
     * @return String
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param String $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
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
     * @return Array
     */
    public function getCases(): array
    {
        return $this->cases;
    }

    /**
     * @param Array $cases
     */
    public function setCases(array $cases): void
    {
        $this->cases = $cases;
    }

    /**
     * @return Array
     */
    public function getCpu(): array
    {
        return $this->cpu;
    }

    /**
     * @param Array $cpu
     */
    public function setCpu(array $cpu): void
    {
        $this->cpu = $cpu;
    }

    /**
     * @return Array
     */
    public function getMotherboard(): array
    {
        return $this->motherboard;
    }

    /**
     * @param Array $motherboard
     */
    public function setMotherboard(array $motherboard): void
    {
        $this->motherboard = $motherboard;
    }

    /**
     * @return Array
     */
    public function getGpu(): array
    {
        return $this->gpu;
    }

    /**
     * @param Array $gpu
     */
    public function setGpu(array $gpu): void
    {
        $this->gpu = $gpu;
    }

    /**
     * @return Array
     */
    public function getMemory(): array
    {
        return $this->memory;
    }

    /**
     * @param Array $memory
     */
    public function setMemory(array $memory): void
    {
        $this->memory = $memory;
    }

    /**
     * @return Array
     */
    public function getPsu(): array
    {
        return $this->psu;
    }

    /**
     * @param Array $psu
     */
    public function setPsu(array $psu): void
    {
        $this->psu = $psu;
    }

    /**
     * @return Array
     */
    public function getStorage(): array
    {
        return $this->storage;
    }

    /**
     * @param Array $storage
     */
    public function setStorage(array $storage): void
    {
        $this->storage = $storage;
    }

    /**
     * @return Array
     */
    public function getRgb(): array
    {
        return $this->rgb;
    }

    /**
     * @return array
     */
    public function getDvd(): array
    {
        return $this->dvd;
    }

    /**
     * @param array $dvd
     */
    public function setDvd(array $dvd): void
    {
        $this->dvd = $dvd;
    }

    /**
     * @param array $rgb
     */
    public function setRgb(array $rgb): void
    {
        $this->rgb = $rgb;
    }

    /**
     * @return array
     */
    public function getOs(): array
    {
        return $this->os;
    }

    /**
     * @param array $os
     */
    public function setOs(array $os): void
    {
        $this->os = $os;
    }

    public function getAllComponents(){
        return  array_merge($this->getCases(),$this->getCpu(),$this->getMotherboard(),$this->getMemory(),$this->getOs(),$this->getStorage()
            ,$this->getGpu(),$this->getRgb(),$this->getPsu(),$this->getDvd());
    }
}