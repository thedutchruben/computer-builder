<?php

namespace PcBuilder\Modules\Controllers;

use PcBuilder\Framework\Execptions\TemplateNotFound;
use PcBuilder\Framework\Registery\Controller;
use PcBuilder\Modules\Managers\ComponentManager;
use PcBuilder\Modules\Managers\ConfigurationManager;
use PcBuilder\Modules\Managers\OrderManager;
use PcBuilder\Objects\Component;
use PcBuilder\Objects\Configurator;
use PcBuilder\Objects\Orders\OrderItems\ConfigrationOrderItem;

class ConfiguratorController extends Controller
{
    private ConfigurationManager $configurationManager;
    private OrderManager $orderManager;
    private ComponentManager $componentManager;

    public function __construct()
    {
        parent::__construct();
        $this->configurationManager = new ConfigurationManager();
        $this->orderManager = new OrderManager();
        $this->componentManager = new ComponentManager();

    }


    /**
     * Route : /configurator/{id}
     * Type : GET
     * Open the configurator
     * @throws TemplateNotFound
     */
    public function Configurator($id){

        $config = $this->configurationManager->getConfig(explode('?',$id)[0]);


        $this->render('Configurator.php',[
            'name' => $config->getName(),
            'description' => $config->getDescription(),
            'cases' => $this->componentManager->getOrderdComponents($config->getCases()),
            'cpu' => $this->componentManager->getOrderdComponents($config->getCpu()),
            'cpucoolers' => $this->componentManager->getOrderdComponents($config->getCpuCoolers()),
            'motherboard' => $this->componentManager->getOrderdComponents($config->getMotherboard()),
            'memory' => $this->componentManager->getOrderdComponents($config->getMemory()),
            'storage' => $this->componentManager->getOrderdComponents($config->getStorage()),
            'storage1' => $this->componentManager->getOrderdComponents($config->getStorage(),true),
            'gpu' => $this->componentManager->getOrderdComponents($config->getGpu()),
            'rgb' => $this->componentManager->getOrderdComponents($config->getRgb(),true),
            'dvd' => $this->componentManager->getOrderdComponents($config->getDvd(),true),
            'psu' => $this->componentManager->getOrderdComponents($config->getPsu()),
            'os' => $this->componentManager->getOrderdComponents($config->getOs()),
            'storageCount' => 2
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
            $item->addPrice($this->componentManager->getPrice($component));
        }
        $this->orderManager->addItemToCart($item);
        header('Location: ' . "/card", true);
    }

}