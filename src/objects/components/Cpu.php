<?php

class Cpu extends Component
{

    public double $baseClock;
    public double $turbo;
    public int $cores;
    public int $threads;

    /**
     * @return float
     */
    public function getBaseClock(): float
    {
        return $this->baseClock;
    }

    /**
     * @param float $baseClock
     */
    public function setBaseClock(float $baseClock): void
    {
        $this->baseClock = $baseClock;
    }

    /**
     * @return float
     */
    public function getTurbo(): float
    {
        return $this->turbo;
    }

    /**
     * @param float $turbo
     */
    public function setTurbo(float $turbo): void
    {
        $this->turbo = $turbo;
    }

    /**
     * @return int
     */
    public function getCores(): int
    {
        return $this->cores;
    }

    /**
     * @param int $cores
     */
    public function setCores(int $cores): void
    {
        $this->cores = $cores;
    }

    /**
     * @return int
     */
    public function getThreads(): int
    {
        return $this->threads;
    }

    /**
     * @param int $threads
     */
    public function setThreads(int $threads): void
    {
        $this->threads = $threads;
    }


}