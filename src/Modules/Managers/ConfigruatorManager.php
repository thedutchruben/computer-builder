<?php

namespace PcBuilder\Modules\Managers;

use PcBuilder\Framework\Registery\Manager;
use PcBuilder\Objects\Component;
use PcBuilder\Objects\Configurator;
use PDO;

class ConfigruatorManager extends Manager
{

    public function getBasicConfugators() : array
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

    public function getConfig($id) : Configurator
    {
        $config = new Configurator();
        $config->setId($id);
        $statement = $this->getMysql()->getPdo()->prepare("SELECT * FROM `pc-builder`.`configs` WHERE `id` = :id;");
        $statement->execute([
            ':id' => $id
        ]);
        $data = $statement->fetch();
        $config->setName($data['name']);
        $config->setDescription($data['description']);
        $config->setCases($this->getConfigItems($id,"cases"));
        $config->setCpu($this->getConfigItems($id,"cpu"));
        $config->setGpu($this->getConfigItems($id,"gpu"));
        $config->setMemory($this->getConfigItems($id,"memory"));
        $config->setPsu($this->getConfigItems($id,"psu"));
        $config->setMotherboard($this->getConfigItems($id,"motherboard"));
        $config->setDvd($this->getConfigItems($id,"dvdplayer"));
        $config->setRgb($this->getConfigItems($id,"rgb"));
        $config->setStorage($this->getConfigItems($id,"disk"));

        return $config;
    }

    function getConfigItems($configId,$type) : ?array
    {
        $array = array();
        $statement = $this->getMysql()->getPdo()->prepare("SELECT * FROM config_components LEFT JOIN components
                                                                    ON config_components.config_id = components.id
                                                                        WHERE `config_id` = :id AND components.type = :type;");
        $statement->execute([
            ':id' => $configId,
            ":type" => $type
        ]);
        foreach ($statement->fetchAll() as $row){
            array_push($array,$row['component_id']);
        }
        return $array;
    }

    function getComponent(int $id) : ?Component
    {

        try {
            $statement = $this->getMysql()->getPdo()->prepare("SELECT * FROM `pc-builder`.`components` WHERE `id` = :id;");
            $statement->execute([
                ':id' => $id
            ]);
            $row = $statement->fetch();
            if($row == false){
                return null;
            }
            $component = new Component($row['id'],$row['displayName']);
            if(isset($row['description'])){
                $component->setDescription($row['description']);
            }

            if(isset($row['image'])){
                $component->setImage($row['image']);
            }
            $component->setPrice($row['price']);
            if(isset($row['powerneed'])){
                $component->setPowerNeed($row['powerneed']);
            }
            return $component;
        }catch (\Exception $exception){
            $this->flasher_error("Er is iets fout gegaan probeer de pagina te reloaden");
        }

        return null;
    }

    function getOrderdComponents($ids,$addNone = false) :array
    {
        $components = array();
        foreach ($ids as $id){
            $component = $this->getComponent($id);
            if($component != null){
                array_push($components,$component);
            }
        }

        if($addNone){
            $free = new Component(999999,"Geen");
            $free->setPrice(0);
            $free->setDescription("This is nothing");
            array_push($components,$free);
        }

        usort($components,function($first,$second){
            return $first->getPrice() > $second->getPrice();
        });

        return $components;
    }

}