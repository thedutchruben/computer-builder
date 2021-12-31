<?php

namespace PcBuilder\Objects;

/**
 * The configurator class contains all the data of the configurator
 * @version 1.0
 * @author Ruben de Roos
 */
class Configurator
{

    /**
     * @var int The database id of the configurator
     */
    private int $id;
    /**
     * @var String The name of the configurator
     */
    private String $name;
    /**
     * @var String The description of the configurator
     */
    private String $description;
    /**
     * @var array All the cases that are selected for the configurator
     */
    private array $cases= [];
    /**
     * @var array All the processors that are selected for the configurator
     */
    private array $cpu = [];
    /**
     * @var array All the processors coolers that are selected for the configurator
     */
    private array $cpuCooler = [];
    /**
     * @var array All the motherboards that are selected for the configurator
     */
    private array $motherboard= [];
    /**
     * @var array All the gpu's that are selected for the configurator
     */
    private array $gpu= [];
    /**
     * @var array All the memory that is selected for the configurator
     */
    private array $memory= [];
    /**
     * @var array All the psu's that are selected for the configurator
     */
    private array $psu= [];
    /**
     * @var array All the storage that is selected for the configurator
     */
    private array $storage= [];
    /**
     * @var array All the rgb that is selected for the configurator
     */
    private array $rgb= [];
    /**
     * @var array All the dvd player's that are selected for the configurator
     */
    private array $dvd= [];
    /**
     * @var array All the os'es that are selected for the configurator
     */
    private array $os= [];

    /**
     * Create an {@link Configurator} object
     * @param int $id the if of the configurator
     */
    public function __construct(int $id)
    {
        $this->id = $id;
    }


    /**
     * Get the id of the configurator
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Get the name of the configurator
     * @return String
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set the name of the configurator
     * @param String $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * Get the description of the configurator
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
     * @return array
     */
    public function getCases(): array
    {
        return $this->cases;
    }

    /**
     * @param array $cases
     */
    public function setCases(array $cases): void
    {
        $this->cases = $cases;
    }

    /**
     * @return array
     */
    public function getCpu(): array
    {
        return $this->cpu;
    }

    /**
     * @param array $cpu
     */
    public function setCpu(array $cpu): void
    {
        $this->cpu = $cpu;
    }

    /**
     * @return array
     */
    public function getMotherboard(): array
    {
        return $this->motherboard;
    }

    /**
     * @param array $motherboard
     */
    public function setMotherboard(array $motherboard): void
    {
        $this->motherboard = $motherboard;
    }

    /**
     * @return array
     */
    public function getGpu(): array
    {
        return $this->gpu;
    }

    /**
     * @param array $gpu
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

    /**
     * @return array
     */
    public function getCpuCoolers(): array
    {
        return $this->cpuCooler;
    }

    /**
     * @param array $cpuCooler
     */
    public function setCpuCooler(array $cpuCooler): void
    {
        $this->cpuCooler = $cpuCooler;
    }

    /**
     * Get all the components items that the configurator has
     * @return array with all the components
     */
    public function getAllComponents(){
        return  array_merge($this->getCases(),$this->getCpu(),$this->getMotherboard(),$this->getMemory(),$this->getOs(),$this->getStorage()
            ,$this->getGpu(),$this->getRgb(),$this->getPsu(),$this->getDvd(),$this->getCpuCoolers());
    }
}