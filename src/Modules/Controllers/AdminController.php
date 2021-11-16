<?php

namespace PcBuilder\Modules\Controllers;

use PcBuilder\Framework\Registery\Controller;
use PcBuilder\Modules\Managers\ConfigurationManager;
use PcBuilder\Modules\Managers\UserManager;
use PcBuilder\Objects\Component;
use PcBuilder\Objects\Configurator;

class AdminController extends Controller
{
    private UserManager $userManager;
    private ConfigurationManager $configurationManager;


    public function __construct()
    {
        parent::__construct();
        $this->userManager = new UserManager();
        $this->configurationManager = new ConfigurationManager();
    }


    /**
     * Route : /admin
     */
    public function index(){
        $this->render('\admin\AdminIndex.php');
    }

    /**
     * Route : /admin/products
     */
    public function products(){
        $this->render('\admin\Products.php',
            [
                "components" => $this->configurationManager->getComponents(),
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
            $this->configurationManager->createComponent($comp,$_POST['tweakersid'],true);
        }else{
            $this->configurationManager->createComponent($comp,$_POST['tweakersid'],false);

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
        $config = new Configurator();
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
                    $this->configurationManager->addConfigOption($id,$comp);
                    $this->flasher_success("<p>Item Added</p>");
                }
            }

            foreach ($config->getAllComponents() as $comp){
                if(!in_array($comp,$_POST['component'])){
                    $this->configurationManager->removeConfigOption($id,$comp);
                    $this->flasher_success("<p>Item removed</p>",
                    [
                        'showTill' => microtime(true) + 10000
                    ]);
                }
            }
        }

        $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
        header('Location: '.$actual_link ."/admin/config/".$id);
    }
}