<?php

namespace PcBuilder\Framework\Database;

use PDO;
use PDOException;

class Mysql
{
    private PDO $PDO;

    public function __construct()
    {
        $servername = $_ENV['MYSQL_HOST'];
        $database = $_ENV['MYSQL_DATABASE'];
        $username = $_ENV['MYSQL_USER'];
        $password = $_ENV['MYSQL_PASSWORD'];

        try {
            $this->PDO = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
            // set the PDO error mode to exception
            $this->PDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }


    public function getPdo() :PDO
    {
        return $this->PDO;
    }

}