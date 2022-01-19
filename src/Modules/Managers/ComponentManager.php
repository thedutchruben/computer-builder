<?php

namespace PcBuilder\Modules\Managers;

use PcBuilder\Framework\Registry\Manager;
use PcBuilder\Objects\Component;

/**
 * Manage the component's
 */
class ComponentManager extends Manager
{

    /**
     * Get the price from a component id
     * @param int $id
     * @return float
     */
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

    /**
     * Get component by id
     * @param int $id
     * @return Component|null
     */
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
            $this->flasher_error("Something went wrong try to refresh",
                [
                    'showTill' => microtime(true) + 1000
                ]);
        }

        return null;
    }

    /**
     * Transfer the int from mysql to a bool in php
     * @param int $int
     * @return bool
     */
    public function transferIntToBool($int) : bool
    {
        return $int == 1;
    }

    /**
     * Create component
     * @param Component $component
     * @param int $tweakers
     * @param bool $enabled
     * @return void
     */
    public function createComponent(Component $component,int $tweakers, bool $enabled){
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


    /**
     * Get component's from the database
     * @param bool $enabled
     * @return array|null
     */
    function getComponents(bool $enabled = true) : ?array
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
            $this->flasher_error("Er is iets fout gegaan probeer de pagina te reloaden",
                [
                    'showTill' => microtime(true) + 1000
                ]);
        }

        return $items;
    }

    /**
     * Link the config and a component
     * @param int $config config id
     * @param int $id component id
     * @return void
     */
    function addConfigOption(int $config,int $id){
        $statement = $this->getMysql()->getPdo()->prepare("INSERT INTO `config_components`(`config_id`, `component_id`) VALUES (:CONFIGID,:COMPONENTID)");
        $statement->execute([
            ':CONFIGID' => $config,
            ':COMPONENTID' => $id
        ]);
    }

    /**
     * Remove the link between a config and a component
     * @param int $config config id
     * @param int $id component id
     * @return void
     */
    function removeConfigOption(int $config, int $id){
        $statement = $this->getMysql()->getPdo()->prepare("DELETE FROM `config_components` WHERE `config_id` = :CONFIGID AND `component_id` = :COMPONENTID");
        $statement->execute([
            ':CONFIGID' => $config,
            ':COMPONENTID' => $id
        ]);
    }


    /**
     * Get component's by type
     * @param string $type
     * @return array
     */
    function getComponentsByType(string $type) :array
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
            $this->flasher_error("Er is iets fout gegaan probeer de pagina te reloaden",
                [
                    'showTill' => microtime(true) + 1000
                ]);
        }

        return $items;
    }


    /**
     * Get the components on price order
     * The addNone can be added if the component is not required
     * @param array $ids
     * @param bool $addNone
     * @return array
     */
    function getOrderedComponents(array $ids, bool $addNone = false) :array
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
            return strlen($second->getPrice()) <=> strlen($first->getPrice());
        });

        return $components;
    }

    /**
     * Update the component data
     * @param Component $component
     * @return void
     */
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