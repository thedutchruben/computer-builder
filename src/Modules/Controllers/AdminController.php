<?php

namespace PcBuilder\Modules\Controllers;

use PcBuilder\Framework\Exception\TemplateNotFound;
use PcBuilder\Framework\Registry\Controller;
use PcBuilder\MailUtil;
use PcBuilder\Modules\Managers\ComponentManager;
use PcBuilder\Modules\Managers\ConfigurationManager;
use PcBuilder\Modules\Managers\OrderManager;
use PcBuilder\Modules\Managers\UserManager;
use PcBuilder\Objects\Component;
use PcBuilder\Objects\Configurator;
use PcBuilder\Objects\User\User;

/**
 *
 */
class AdminController extends Controller
{
    /**
     * @var UserManager
     */
    private UserManager $userManager;
    /**
     * @var ConfigurationManager
     */
    private ConfigurationManager $configurationManager;
    /**
     * @var OrderManager
     */
    private OrderManager $orderManager;
    /**
     * @var ComponentManager
     */
    private ComponentManager $componentManager;
    /**
     * @var User|null
     */
    private ?User $user;

    /**
     * This code will always get executed before the user gets to a page
     */
    public function __construct()
    {
        parent::__construct();
        $this->userManager = new UserManager();
        $this->configurationManager = new ConfigurationManager();
        $this->orderManager = new OrderManager();
        $this->componentManager = new ComponentManager();
        $this->user = $this->userManager->getSessionUser();
        $this->checkAuth();
    }

    /**
     * Check if the user is authenticated and has not a Customer rank
     * @return void
     */
    public function checkAuth(){
        if(!isset($this->user)){
            $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
            header('Location: '.$actual_link ."/login");
        }

        if($this->user->getUserType() == "Customer"){
            $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
            header('Location: '.$actual_link ."/customer");
        }
    }

    /**
     * The admin home page with check of prices
     *
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
                }else if(doubleval($currentPrice) > doubleval($component->getPrice()) ||  doubleval($component->getPrice()) - doubleval($currentPrice) > doubleval($_ENV['MAX_PRICE_DIFF'])){
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

    /**
     * Get the edit page for a product
     * @param int $id
     * @return void
     * @throws TemplateNotFound
     */
    public function editProduct(int $id){
        $component = $this->componentManager->getComponent($id);
        $this->render('\admin\Product.php',[
            'component' => $component,
            'currentPrice' =>  $this->configurationManager->getCurrentPrice($component)
        ]);
    }

    /**
     * Get all the products/components
     * Route : /admin/products
     */
    public function products(){
        $this->render('\admin\Products.php',
            [
                "components" => $this->componentManager->getComponents(false),
            ]);
    }

    /**
     * Get all the configurators
     * Route : /admin/configs
     */
    public function configs(){
        $this->render('\admin\Configurators.php',
            [
                "configs" => $this->configurationManager->getBasicConfigurator(),
            ]);
    }

    /**
     * Get all orders
     * Route : /admin/orders
     */
    public function orders(){
        $this->render('\admin\Orders.php',
            [
                "orders" => $this->orderManager->getOrders(),
            ]);
    }


    /**
     * End point to create a product
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

    /**
     * Open a config page to edit the configurator
     * @param $id
     * @return void
     * @throws TemplateNotFound
     */
    public function config($id){
        $this->render('\admin\Configurator.php',
            [
                "config" => $this->configurationManager->getConfig($id),
            ]);
    }


    /**
     * Endpoint to create a config
     * @return void
     */
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

    /**
     * Save the configurator
     * @param int $id
     * @return void
     */
    public function saveConfig(int $id){
        $config = $this->configurationManager->getConfig($id);
        if(isset($_POST['component'])){
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

    /**
     * Endpoint to update a product
     * @return void
     */
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
                'showTill' => microtime(true) + 200
            ]);
        $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
        header('Location: '.$actual_link ."/admin/products/");
    }

    /**
     * Get order info of an order
     * @param int $id
     * @return void
     * @throws TemplateNotFound
     */
    public function orderInfo(int $id){
        $order = $this->orderManager->getOrder($id);
        $this->render('\admin\Order.php',
            [
                "order" => $order,
                "customer" => $this->userManager->getUser($order->getCustomerId())
            ]);
    }

    /**
     * Endpoint to update an order
     * @param int $id
     * @return void
     */
    public function updateOrder(int $id){
        $order = $this->orderManager->getOrder($id);
        $user = $this->userManager->getUser($order->getCustomerId());
        if(isset($_POST['paid']) && (!$order->isPaid())){
            $order->setPaid(true);
            if(isset($_POST['update_customer'])){
                $mail = new MailUtil('Payment confirm','PCBuilder');
                $mail->getMessage()->addPart(file_get_contents($_SERVER['DOCUMENT_ROOT']  . "\pages\mails\status\PaymentConfirmMail.html"),'text/html');
                $mail->send($user->getEmail());
            }
        }

        if($_POST['status'] != $order->getStatus()){
            $order->setStatus($_POST['status']);

            switch ($_POST['status']){
                case "IN_PRODUCTION":
                    $mail = new MailUtil('Order update','PCBuilder');
                    $mail->getMessage()->addPart(file_get_contents($_SERVER['DOCUMENT_ROOT']  . "\pages\mails\status\OrderInProductionMail.html"),'text/html');
                    $mail->send($user->getEmail());
                    break;
                case "SEND":
                    $mail = new MailUtil('Order update','PCBuilder');
                    $mail->getMessage()->addPart(file_get_contents($_SERVER['DOCUMENT_ROOT']  . "\pages\mails\status\OrderSendMail.html"),'text/html');
                    $mail->send($user->getEmail());
                    break;
            }
        }


        $this->orderManager->updateOrder($order);
        $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
        header('Location: '.$actual_link ."/admin/order/" .$id);
    }
}