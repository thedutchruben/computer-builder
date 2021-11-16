<?php

namespace PcBuilder\Modules\Managers;

use PcBuilder\Framework\Registery\Manager;
use PcBuilder\Objects\Component;
use PcBuilder\Objects\Configurator;

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
        $config = new Configurator();
        $config->setId($id);
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

    public function getPrice(int $id) : float
    {
        $statement = $this->getMysql()->getPdo()->prepare("SELECT `price` FROM `pc-builder`.`components` WHERE `id` = :id;");
        $statement->execute([
            ':id' => $id
        ]);
        $row = $statement->fetch();
        if($row == false){
            return 0;
        }
        return $row['price'];
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

            if(isset($row['type'])){
                $component->setType($row['type']);
            }

            return $component;
        }catch (\Exception $exception){
            $this->flasher_error("Er is iets fout gegaan probeer de pagina te reloaden");
        }

        return null;
    }

    public function createComponent(Component $component,$tweakers,$enabled){
        $statement = $this->getMysql()->getPdo()->prepare("INSERT INTO `components`( `displayName`, `description`, `image`, `price`, `powerneed`, `type`, `tweakers_id`,`enabled`) 
                                                            VALUES (:name,:description,:image,:price,:power,:type,:tweakers,:enabled)");
        $statement->execute([
            ':name' => $component->getDisplayName(),
            ':description' => $component->getDescription(),
            ':image' => $component->getRawImage(),
            ':price' => $component->getPrice(),
            ':power' => $component->getPowerNeed(),
            ':type' => $component->getType(),
            ':tweakers' => $tweakers,
            ':enabled' => $enabled,
        ]);
    }



    function getComponents() : ?array
    {

        $items = [];

        try {
            $statement = $this->getMysql()->getPdo()->prepare("SELECT * FROM `pc-builder`.`components`");
            $statement->execute();
            foreach ($statement->fetchAll() as $row){
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
                if(isset($row['type'])){
                    $component->setType($row['type']);
                }

                array_push($items,$component);
            }



        }catch (\Exception $exception){
            $this->flasher_error("Er is iets fout gegaan probeer de pagina te reloaden");
        }

        return $items;
    }

    function addConfigOption($config,$id){
        $statement = $this->getMysql()->getPdo()->prepare("INSERT INTO `config_components`(`config_id`, `component_id`) VALUES (:CONFIGID,:COMPONENTID)");
        $statement->execute([
            ':CONFIGID' => $config,
            ':COMPONENTID' => $id
        ]);
    }

    function removeConfigOption($config,$id){
        $statement = $this->getMysql()->getPdo()->prepare("DELETE FROM `config_components` WHERE `config_id` = :CONFIGID AND `component_id` = :COMPONENTID");
        $statement->execute([
            ':CONFIGID' => $config,
            ':COMPONENTID' => $id
        ]);
    }


    function getComponentsByType($type) :array
    {
        $items = [];

        try {
            $statement = $this->getMysql()->getPdo()->prepare("SELECT * FROM `pc-builder`.`components` where `type` = :type");
            $statement->execute([
                    ':type' => $type
                ]
            );
            foreach ($statement->fetchAll() as $row){
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
                if(isset($row['type'])){
                    $component->setType($row['type']);
                }

                array_push($items,$component);
            }



        }catch (\Exception $exception){
            $this->flasher_error("Er is iets fout gegaan probeer de pagina te reloaden");
        }

        return $items;
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