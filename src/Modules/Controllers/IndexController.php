<?php

namespace PcBuilder\Modules\Controllers;

use PcBuilder\Framework\Registery\Controller;
use PcBuilder\Modules\Managers\ConfigurationManager;
use PcBuilder\Modules\Managers\OrderManager;
use PcBuilder\Modules\Managers\UserManager;
use PcBuilder\Objects\User\User;

class IndexController extends Controller
{
    private ConfigurationManager $configruatorManager;
    private OrderManager $orderManager;
    private UserManager $userManager;
    public function __construct()
    {
        parent::__construct();
        $this->configruatorManager = new ConfigurationManager();
        $this->orderManager = new OrderManager();
        $this->userManager = new UserManager();
    }

    /**
     * Route : /
     */
    public function index(){
        $this->render('HomePage.php',[
            'configs' => $this->configruatorManager->getBasicConfigurator(),
        ]);
        $this->flasher_success(
            "<h2>Korting!</h2></br><p>Gebruik nu de code <code>10AF</code> om 10% korting te krijgen op je aankoop</p>",[
                "oneTimeSession" => true
            ]
        );
    }

    /**
     * Route : /sitemap
     *
     * This will show the sitemap
     */
    public function siteMap(){

    }

    /**
     * Route : /contact
     *
     * This will show the sitemap
     */
    public function contact(){

    }

    /**
     * Route : /card
     *
     * This will show the sitemap
     */
    public function card(){
        $this->render('CardPage.php');
    }

    /**
     * Route : /checkout
     *
     * This will show the sitemap
     */
    public function checkout(){
        if($this->userManager->is_authenticated()){
            $this->render('Checkout.php');
        }else{
            header('Location: ' . "/login", true);
            $this->flasher_success("Login before ordering!",[
                "showTill" => microtime(true) + 5000
            ]);
        }
    }

    /**
     * Route : /checkout
     *
     * This will show the sitemap
     */
    public function checkout_post(){
        $user = $this->userManager->getSessionUser();
        $order = $this->orderManager->placeOrder($user,$this->orderManager->getShoppingCart()->getItems());
        if($order == null){
            header('Location: ' . "/card", true);
            $this->flasher_error("<h2>Someting went wrong</h2><\br> <p>It was not possible to register the order</p>");
        }else{
            header('Location: ' . "/customer/order/".$order, true);
        }
    }

}