<?php

namespace PcBuilder\Framework\Cache;

/**
 * Cache object is the cache file in a object
 */
class CacheObject
{
    /**
     * The id of the object
     * @var string
     */
    public string $id;
    /**
     * The end time for validation
     * @var float
     */
    public float $endTime;
    /**
     * The data of the object
     * @var mixed
     */
    public mixed $data;

    /**
     * Setting the data of the cache
     * @param $data
     * @return void
     */
    public function setData($data){
        $this->data = $data;
    }

    /**
     * Get the data of the cache
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Get the end time of the cache object
     * @return float
     */
    public function getEndTime(): float
    {
        return $this->endTime;
    }

    /**
     * Set the end time of the cache
     * @param float $endTime
     */
    public function setEndTime(float $endTime): void
    {
        $this->endTime = $endTime;
    }

    /**
     * set the id of the cache object
     * @param string $id
     */
    public function setId(string $id): void
    {
        $this->id = $id;
    }

    /**
     * Save the cache object to a file
     * @param $object
     * @return void
     */
    public function save($object)
    {
        $cacheFile = fopen("cache/".$this->id.".json", "w");
        fwrite($cacheFile, json_encode($object));
        fclose($cacheFile);
    }
}