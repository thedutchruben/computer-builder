<?php

namespace PcBuilder\Modules\Controllers;

use PcBuilder\Framework\Exception\TemplateNotFound;
use PcBuilder\Framework\Registry\Controller;
use PcBuilder\Modules\Managers\ConfigurationManager;
use PcBuilder\Modules\Managers\OrderManager;
use PcBuilder\Modules\Managers\UserManager;
use PcBuilder\Objects\User\User;

/**
 * The index controller will handle all the default pages that you can reach
 */
class IndexController extends Controller
{
    /**
     * A link to the configuration manager
     * @var ConfigurationManager
     */
    private ConfigurationManager $configuratorManager;
    /**
     * A link to the order manager
     * @var OrderManager
     */
    private OrderManager $orderManager;
    /**
     * A link to the user manager
     * @var UserManager
     */
    private UserManager $userManager;

    /**
     * Setting up all the needed links for the index controller
     */
    public function __construct()
    {
        parent::__construct();
        $this->configuratorManager = new ConfigurationManager();
        $this->orderManager = new OrderManager();
        $this->userManager = new UserManager();
    }

    /**
     * Route : /
     */
    public function index(){
        $this->render('HomePage.php',[
            'configs' => $this->configuratorManager->getBasicConfigurator(),
        ]);
        $this->flasher_success(
            "<h2>Korting!</h2></br><p>Gebruik nu de code <code>10AF</code> om 10% korting te krijgen op je aankoop</p>",[
                "oneTimeSession" => true
            ]
        );
    }


    /**
     * Route : /cart
     *
     * This will show the shopping cart
     * @throws TemplateNotFound
     */
    public function cart(){
        $this->render('CartPage.php');
    }

    /**
     * Route : /checkout
     *
     * Render the checkout page
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
     * This will register an order
     */
    public function checkout_post(){
        $user = $this->userManager->getSessionUser();
        $order = $this->orderManager->placeOrder($user,$this->orderManager->getShoppingCart()->getItems());
        if($order == null){
            header('Location: ' . "/cart", true);
            $this->flasher_error("<h2>Someting went wrong</h2><\br> <p>It was not possible to register the order</p>",
                [
                    'showTill' => microtime(true) + 1000
                ]);
        }else{
            header('Location: ' . "/customer/order/".$order, true);
        }
    }

}