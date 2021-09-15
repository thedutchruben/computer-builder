<?php

namespace PcBuilder\Modules\Controllers;

use PcBuilder\Framework\Registery\Controller;
use PcBuilder\Modules\Managers\ConfigruatorManager;
use PcBuilder\Objects\Component;
use PcBuilder\Objects\Configurator;

class ConfiguratorController extends Controller
{
    private ConfigruatorManager $manager;

    public function __construct()
    {
        parent::__construct();
        $this->manager = new ConfigruatorManager();
    }


    /**
     * Route : /configurator/{name}
     *
     * Open the configurator
     */
    public function Configurator($name){
        $config = new Configurator();
        $config->setName($name);
        $config->setDescription("Test base");
        $array = array();
        foreach (range(1,606) as $i){
            array_push($array,$i);
        }
        $config->setCases($array);
        $cpu = array();
        foreach (range(607,620) as $i){
            array_push($cpu,$i);
        }
        $config->setCpu($cpu);
        $this->render('Configurator.php',[
            'name' => $config->getName(),
            'description' => $config->getDescription(),
            'cases' => $this->manager->getOrderdComponents($config->getCases()),
            'cpu' => $this->manager->getOrderdComponents($config->getCpu()),
            'motherboard' => $this->manager->getOrderdComponents($config->getMotherboard()),
            'memory' => $this->manager->getOrderdComponents($config->getMemory()),
            'storage' => $this->manager->getOrderdComponents($config->getStorage()),
            'rgb' => $this->manager->getOrderdComponents($config->getRgb(),true),
            'dvd' => $this->manager->getOrderdComponents($config->getRgb(),true),
            'psu' => $this->manager->getOrderdComponents($config->getPsu()),
        ]);
    }

}