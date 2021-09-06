<?php

namespace PcBuilder\Framework\Registery;

use PcBuilder\Framework\Database\Mysql;

class RegisteryBase
{

    private Mysql $mysql;

    public function __construct()
    {
        $this->mysql = new Mysql();
    }

    /**
     * @return Mysql
     */
    public function getMysql(): Mysql
    {
        return $this->mysql;
    }

}