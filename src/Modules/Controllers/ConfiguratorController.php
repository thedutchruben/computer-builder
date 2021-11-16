<?php

namespace PcBuilder\Modules\Controllers;

use PcBuilder\Framework\Registery\Controller;
use PcBuilder\Modules\Managers\ConfigurationManager;
use PcBuilder\Modules\Managers\OrderManager;
use PcBuilder\Objects\Component;
use PcBuilder\Objects\Configurator;
use PcBuilder\Objects\Orders\OrderItems\ConfigrationOrderItem;

class ConfiguratorController extends Controller
{
    private ConfigurationManager $manager;
    private OrderManager $orderManager;

    public function __construct()
    {
        parent::__construct();
        $this->manager = new ConfigurationManager();
        $this->orderManager = new OrderManager();
    }



    /**
     * Route : /configurator/{id}
     * Type : GET
     * Open the configurator
     */
    public function Configurator($id){

        $config = $this->manager->getConfig(explode('?',$id)[0]);


        $this->render('Configurator.php',[
            'name' => $config->getName(),
            'description' => $config->getDescription(),
            'cases' => $this->manager->getOrderdComponents($config->getCases()),
            'cpu' => $this->manager->getOrderdComponents($config->getCpu()),
            'motherboard' => $this->manager->getOrderdComponents($config->getMotherboard()),
            'memory' => $this->manager->getOrderdComponents($config->getMemory()),
            'storage' => $this->manager->getOrderdComponents($config->getStorage()),
            'gpu' => $this->manager->getOrderdComponents($config->getGpu()),
            'rgb' => $this->manager->getOrderdComponents($config->getRgb(),true),
            'dvd' => $this->manager->getOrderdComponents($config->getDvd(),true),
            'psu' => $this->manager->getOrderdComponents($config->getPsu()),
            'os' => $this->manager->getOrderdComponents($config->getOs())
        ]);
    }

    /**
     * Route : /configurator/{id}
     * Type : POST
     * Open the configurator
     */
    public function Configurator_Post($id){
        $item = new ConfigrationOrderItem($_POST['pcName'],1,json_decode($_POST['config'],true));
        foreach ($item->getComponents() as $component){
            $item->addPrice($this->manager->getPrice($component));
        }
        $this->orderManager->addItemToCard($item);
        header('Location: ' . "/card", true);
    }

}