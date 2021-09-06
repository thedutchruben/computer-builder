<?php

namespace PcBuilder\Framework\Database;

class Mysql
{
    private \PDO $PDO;

    public function __construct()
    {
        $this->PDO = new \PDO();
    }


    public function getPdo() :\PDO
    {
        return $this->PDO;
    }

}