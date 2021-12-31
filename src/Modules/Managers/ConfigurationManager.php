<?php

namespace PcBuilder\Modules\Managers;

use PcBuilder\Framework\Registery\Manager;
use PcBuilder\Objects\Component;
use PcBuilder\Objects\Configurator;
use PcBuilder\Objects\Orders\Order;

class ConfigurationManager extends Manager
{

    public function getBasicConfigurator() : array
    {
        $config = array();
        $statement = $this->getMysql()->getPdo()->prepare("SELECT `id`,`name`,`image`,`basePrice` FROM `configs`");
        $statement->execute();
        foreach ($statement->fetchAll() as $row){
            $data = [];
            $data['id'] = $row['id'];
            $data['name'] = $row['name'];
            $data['price'] = $row['basePrice'];
            $data['image'] = $row['image'];
            array_push($config,$data);
        }
        return $config;
    }

    public function createConfig(Configurator $configurator,$price,$image){
        $statement = $this->getMysql()->getPdo()->prepare("INSERT INTO `configs`(`name`, `basePrice`, `image`, `description`) VALUES (:name,:basePrice,:image,:description);");
        $statement->execute([
            ':name' => $configurator->getName(),
            ':basePrice' => $price,
            ':image' => $image,
            ':description' => $configurator->getDescription()
        ]);
    }


    public function getConfig($id) : Configurator
    {
        $config = new Configurator($id);
        $statement = $this->getMysql()->getPdo()->prepare("SELECT * FROM `pc-builder`.`configs` WHERE `id` = :id;");
        $statement->execute([
            ':id' => $id
        ]);
        $data = $statement->fetch();
        $config->setName($data['name']);
        $config->setDescription($data['description']);
        $config->setCases($this->getConfigItems($id,"CASE"));
        $config->setCpu($this->getConfigItems($id,"CPU"));
        $config->setGpu($this->getConfigItems($id,"GRAPHICSCARD"));
        $config->setMemory($this->getConfigItems($id,"RAM"));
        $config->setPsu($this->getConfigItems($id,"PSU"));
        $config->setMotherboard($this->getConfigItems($id,"MOTHERBOARD"));
        $config->setDvd($this->getConfigItems($id,"DVDPLAYER"));
        $config->setRgb($this->getConfigItems($id,"RGB"));
        $config->setStorage($this->getConfigItems($id,"STORAGE"));
        $config->setOs($this->getConfigItems($id,"OS"));
        $config->setCpuCooler($this->getConfigItems($id,"CPUCOOLER"));

        return $config;
    }

    function getConfigItems($configId,$type) : ?array
    {
        $array = array();
        $statement = $this->getMysql()->getPdo()->prepare("SELECT * FROM config_components LEFT JOIN components
                                                                    ON config_components.component_id = components.id
                                                                        WHERE `config_id` = :id AND components.type = :type AND 
                                                                              components.enabled = true;");
        $statement->execute([
            ':id' => $configId,
            ":type" => $type
        ]);
        foreach ($statement->fetchAll() as $row){
            array_push($array,$row['component_id']);
        }
        return $array;
    }




    public function getCurrentPrice(Component $component){
        if($component->getTweakersId() == -1) return "0";
        $cache = $this->getCache("prices-" . $component->getTweakersId(),1000*60,null);
        if($cache->getData() != null){
            return $cache->getData();
        }
        $info = file_get_contents("https://privateapi.thedutchruben.nl/api/v1/getprice/" . $component->getTweakersId());
        $cache->setData($info);
        if($info != "Price timeout"){
            $cache->save($cache);
        }

        return str_replace('.',',',$info);
    }
}