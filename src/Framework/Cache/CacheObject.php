<?php

namespace PcBuilder\Framework\Cache;

class CacheObject
{
    public string $id;
    public float $endTime;
    public mixed $data;

    public function setData($data){
        $this->data = $data;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return float
     */
    public function getEndTime(): float
    {
        return $this->endTime;
    }

    /**
     * @param float $endTime
     */
    public function setEndTime(float $endTime): void
    {
        $this->endTime = $endTime;
    }

    /**
     * @param string $id
     */
    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function save($object)
    {
        $cacheFile = fopen("cache/".$this->id.".json", "w");
        fwrite($cacheFile, json_encode($object));
        fclose($cacheFile);
    }
}