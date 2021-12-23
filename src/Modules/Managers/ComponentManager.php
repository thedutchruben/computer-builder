<?php

namespace PcBuilder\Modules\Managers;

use PcBuilder\Framework\Registery\Manager;
use PcBuilder\Objects\Component;

class ComponentManager extends Manager
{

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

            if(isset($row['tweakers_id'])){
                $component->setTweakersId($row['tweakers_id']);
            }

            if(isset($row['enabled'])){
                $component->setEnabled($this->transferIntToBool($row['enabled']));
            }

            return $component;
        }catch (\Exception $exception){
            $this->flasher_error("Er is iets fout gegaan probeer de pagina te reloaden");
        }

        return null;
    }

    public function transferIntToBool($int) : bool
    {
        return $int == 1 ? true : false;
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



    function getComponents($enabled = true) : ?array
    {

        $items = [];

        try {
            if($enabled){
                $statement = $this->getMysql()->getPdo()->prepare("SELECT * FROM `pc-builder`.`components` where `enabled` = true");
            }else{
                $statement = $this->getMysql()->getPdo()->prepare("SELECT * FROM `pc-builder`.`components`");
            }
            $statement->execute();
            foreach ($statement->fetchAll() as $row){
                $component = new Component($row['id'],$row['displayName']);
                if(isset($row['description'])){
                    $component->setDescription($row['description']);
                }

                if(isset($row['image'])){
                    $component->setImage($row['image']);
                }

                if(isset($row['tweakers_id'])){
                    $component->setTweakersId($row['tweakers_id']);
                }

                $component->setPrice($row['price']);
                if(isset($row['powerneed'])){
                    $component->setPowerNeed($row['powerneed']);
                }
                if(isset($row['type'])){
                    $component->setType($row['type']);
                }

                if(isset($row['enabled'])){
                    $component->setEnabled($row['enabled']);
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

                if(isset($row['tweakers_id'])){
                    $component->setTweakersId($row['tweakers_id']);
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

                if(isset($row['enabled'])){
                    $component->setEnabled($row['enabled']);
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
            $free = new Component(-1,"Geen");
            $free->setPrice(0);
            $free->setDescription("This is nothing");
            array_push($components,$free);
        }

        usort($components,function($first,$second){
            return $first->getPrice() > $second->getPrice();
        });

        return $components;
    }

    public function updateComponent(Component $component){
        $statement = $this->getMysql()->getPdo()->prepare("UPDATE `components` SET 
                      `displayName`=:name,
                      `description`=:description,
                      `image`=:image,
                      `price`=:price,
                      `powerneed`=:power,
                      `type`=:type,
                      `tweakers_id`=:tweakers,
                      `enabled`=:enabled 
                        WHERE `id` = :ID");
        $statement->execute([
            ':ID' => $component->getId(),
            ':name' => $component->getDisplayName(),
            ':description' => $component->getDescription(),
            ':image' => $component->getRawImage(),
            ':price' => $component->getPrice(),
            ':power' => $component->getPowerNeed(),
            ':type' => $component->getType(),
            ':tweakers' => $component->getTweakersId(),
            ':enabled' => $component->isEnabled(),
        ]);
    }

}