<?php

namespace PcBuilder\Modules\Controllers;

use PcBuilder\Framework\Execptions\TemplateNotFound;
use PcBuilder\Framework\Registery\Controller;
use PcBuilder\Modules\Managers\ComponentManager;
use PcBuilder\Modules\Managers\ConfigurationManager;
use PcBuilder\Modules\Managers\OrderManager;
use PcBuilder\Objects\Component;
use PcBuilder\Objects\Configurator;
use PcBuilder\Objects\Orders\OrderItems\ConfigurationOrderItem;

/**
 * Handle all the endpoints for the configurators
 */
class ConfiguratorController extends Controller
{
    /**
     * A link to the configuration manager
     * @var ConfigurationManager
     */
    private ConfigurationManager $configurationManager;
    /**
     * A link to the order manager
     * @var OrderManager
     */
    private OrderManager $orderManager;
    /**
     * A link to the component manager
     * @var ComponentManager
     */
    private ComponentManager $componentManager;

    /**
     * Setting up all the link's to the manager's
     */
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
            'cases' => $this->componentManager->getOrderedComponents($config->getCases()),
            'cpu' => $this->componentManager->getOrderedComponents($config->getCpu()),
            'cpucoolers' => $this->componentManager->getOrderedComponents($config->getCpuCoolers()),
            'motherboard' => $this->componentManager->getOrderedComponents($config->getMotherboard()),
            'memory' => $this->componentManager->getOrderedComponents($config->getMemory()),
            'storage' => $this->componentManager->getOrderedComponents($config->getStorage()),
            'storage1' => $this->componentManager->getOrderedComponents($config->getStorage(),true),
            'gpu' => $this->componentManager->getOrderedComponents($config->getGpu()),
            'rgb' => $this->componentManager->getOrderedComponents($config->getRgb(),true),
            'dvd' => $this->componentManager->getOrderedComponents($config->getDvd(),true),
            'psu' => $this->componentManager->getOrderedComponents($config->getPsu()),
            'os' => $this->componentManager->getOrderedComponents($config->getOs()),
            'storageCount' => 2
        ]);
    }

    /**
     * Route : /configurator/{id}
     * Type : POST
     * Open the configurator
     */
    public function Configurator_Post($id){
        $item = new ConfigurationOrderItem($_POST['pcName'],1,json_decode($_POST['config'],true));
        foreach ($item->getComponents() as $component){
            $item->addPrice($this->componentManager->getPrice($component));
        }
        $this->orderManager->addItemToCart($item);
        header('Location: ' . "/cart", true);
    }

}