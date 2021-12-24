<?php

namespace PcBuilder\Modules\Controllers;

use PcBuilder\Framework\Registery\Controller;
use PcBuilder\Modules\Managers\ComponentManager;
use PcBuilder\Modules\Managers\ConfigurationManager;
use PcBuilder\Modules\Managers\OrderManager;
use PcBuilder\Modules\Managers\UserManager;
use PcBuilder\Objects\Component;
use PcBuilder\Objects\Configurator;

class AdminController extends Controller
{
    private UserManager $userManager;
    private ConfigurationManager $configurationManager;
    private OrderManager $orderManager;
    private ComponentManager $componentManager;


    public function __construct()
    {
        parent::__construct();
        $this->userManager = new UserManager();
        $this->configurationManager = new ConfigurationManager();
        $this->orderManager = new OrderManager();
        $this->componentManager = new ComponentManager();
    }


    /**
     * Route : /admin
     */
    public function index(){
        $wrongPrice = [];
        $notAllLoaded = false;
        $notLoader = 0;
        $notBuyAble = 0;
        foreach ($this->componentManager->getComponents() as $component){
            $currentPrice = $this->configurationManager->getCurrentPrice($component);
            if($currentPrice == "Price timeout"){
                $notAllLoaded = true;
                $notLoader++;
            }else{
                if($currentPrice == "No Price"){
                    $notBuyAble++;
                    array_push($wrongPrice,[
                        'id' => $component->getId(),
                        'name' => $component->getDisplayName(),
                        'price' => "not Available",
                        'currentPrice' => doubleval($currentPrice)
                    ]);
                }else if(doubleval($currentPrice) > doubleval($component->getPrice())){
                    array_push($wrongPrice,[
                        'id' => $component->getId(),
                        'name' => $component->getDisplayName(),
                        'price' => $component->getPrice(),
                        'currentPrice' => doubleval($currentPrice)
                    ]);

                }
            }

        }
        $this->render('\admin\AdminIndex.php',[
            'wrongPrice' => $wrongPrice,
            'openOrders' => $this->orderManager->getOpenOrderCount(),
            'productionOrders' => $this->orderManager->getProductionOrderCount()
        ]);
        if($notAllLoaded){
            $this->flasher_error("<h2>Error</h2><br><p>Not all data is loaded right! (".$notLoader.")</p>");
        }
    }

    public function editProduct($id){
        $component = $this->componentManager->getComponent($id);
        $this->render('\admin\Product.php',[
            'component' => $component,
            'currentPrice' =>  $this->configurationManager->getCurrentPrice($component)
        ]);
    }

    /**
     * Route : /admin/products
     */
    public function products(){
        $this->render('\admin\Products.php',
            [
                "components" => $this->componentManager->getComponents(false),
            ]);
    }

    /**
     * Route : /admin/configs
     */
    public function configs(){
        $this->render('\admin\Configurators.php',
            [
                "configs" => $this->configurationManager->getBasicConfigurator(),
            ]);
    }

    /**
     * Route : /admin/orders
     */
    public function orders(){
        $this->render('\admin\Orders.php',
            [
                "orders" => $this->orderManager->getOrders(),
            ]);
    }


    /**
     * Route: /admin/product/create
     *
     */
    public function registerProduct(){
        $comp = new Component(-123,$_POST['name']);
        $comp->setDescription($_POST['description']);
        $comp->setImage("assets/uploads/".$_FILES['image']['name']);
        $comp->setPrice($_POST['price']);
        $comp->setPowerNeed($_POST['power']);
        $comp->setType($_POST['type']);
        if(isset($_POST['enabled'])){
            $this->componentManager->createComponent($comp,$_POST['tweakersid'],true);
        }else{
            $this->componentManager->createComponent($comp,$_POST['tweakersid'],false);

        }
        $file_name = $_FILES['image']['name'];
        $file_tmp = $_FILES['image']['tmp_name'];
        move_uploaded_file($file_tmp,"assets/uploads/".$file_name);
        $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
        header('Location: '.$actual_link ."/admin/products");
    }

    public function config($id){
        $this->render('\admin\Configurator.php',
            [
                "config" => $this->configurationManager->getConfig($id),
            ]);
    }


    public function createConfig(){
        $file_name = $_FILES['image']['name'];
        $file_tmp = $_FILES['image']['tmp_name'];
        move_uploaded_file($file_tmp,"assets/uploads/".$file_name);
        $config = new Configurator(-1);
        $config->setName($_POST['name']);
        $config->setDescription($_POST['description']);
        $this->configurationManager->createConfig($config,$_POST['price'],"assets/uploads/".$_FILES['image']['name']);
        $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
        header('Location: '.$actual_link ."/admin/configs");
    }

    public function saveConfig($id){
        $config = $this->configurationManager->getConfig($id);
        if(!isset($_POST['component'])){

        }else{
            foreach ($_POST['component'] as $comp){
                if(!in_array($comp,$config->getAllComponents())){
                    $this->componentManager->addConfigOption($id,$comp);
                    $this->flasher_success("<p>Item Added</p>",
                        [
                            'showTill' => microtime(true) + 20
                        ]);
                }
            }

            foreach ($config->getAllComponents() as $comp){
                if(!in_array($comp,$_POST['component'])){
                    $this->componentManager->removeConfigOption($id,$comp);
                    $this->flasher_success("<p>Item removed</p>",
                    [
                        'showTill' => microtime(true) + 20
                    ]);
                }
            }
        }

        $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
        header('Location: '.$actual_link ."/admin/config/".$id);
    }

    public function updateProduct(){
        $component = new Component($_POST['id'],$_POST['name']);
        $component->setDescription($_POST['description']);
        $component->setImage($_POST['image']);
        $component->setPrice($_POST['price']);
        $component->setPowerNeed($_POST['power']);
        $component->setType($_POST['type']);
        $component->setTweakersId($_POST['tweakersid']);
        $active = false;
        if(isset($_POST['enabled'])){
            $active = true;
        }

        $component->setEnabled($active);
        $this->componentManager->updateComponent($component);
        $this->flasher_success("<p>Item Updated</p>",
            [
                'showTill' => microtime(true) + 20
            ]);
        $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
        header('Location: '.$actual_link ."/admin/products/");
    }

    public function orderInfo($id){
        $this->render('\admin\Order.php',
            [
                "order" => $this->orderManager->getOrder($id),
            ]);
    }
}