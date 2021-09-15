<?php

namespace PcBuilder\Modules\Managers;

use PcBuilder\Framework\Registery\Manager;
use PcBuilder\Objects\Component;
use PDO;

class ConfigruatorManager extends Manager
{

    public function getCases() : array
    {
        $cases = array();
        try {
            $statement = $this->getMysql()->getPdo()->prepare("SELECT * FROM `pc-builder`.`components` WHERE `type` = :type;");
            $statement->execute([
                ':type' => "PC_CASE"
            ]);
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
                array_push($cases,$component);
            }
        }catch (PDOException $exception){
            $this->flasher_error("Er is iets fout gegaan probeer de pagina te reloaden");
        }

        return $cases;
    }

    function getComponent(int $id) : ?Component
    {

        try {
            $statement = $this->getMysql()->getPdo()->prepare("SELECT * FROM `pc-builder`.`components` WHERE `id` = :id;");
            $statement->execute([
                ':id' => $id
            ]);
            $row = $statement->fetch();
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
        }catch (PDOException $exception){
            $this->flasher_error("Er is iets fout gegaan probeer de pagina te reloaden");
        }

        return null;
    }

    function getOrderdComponents($ids,$addNone = false) :array
    {
        $components = array();
        foreach ($ids as $id){
            array_push($components,$this->getComponent($id));
        }

        if($addNone){
            $free = new Component(999999,"Geen");
            $free->setPrice(0);
            $free->setDescription("Deze optie regeld dat je niet toevoegd");
            array_push($components,$free);
        }

        usort($components,function($first,$second){
            return $first->getPrice() > $second->getPrice();
        });

        return $components;
    }

}