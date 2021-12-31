<?php

namespace PcBuilder\Modules\Controllers;

use PcBuilder\Framework\Registery\Controller;
use PcBuilder\Modules\Managers\OrderManager;
use PcBuilder\Modules\Managers\UserManager;
use PcBuilder\Objects\User\User;

class CustomerController extends Controller
{
    private OrderManager $orderManager;
    private UserManager $userManager;
    private ?User $user;

    public function __construct()
    {
        parent::__construct();
        $this->orderManager = new OrderManager();
        $this->userManager = new UserManager();
        $this->user = $this->userManager->getSessionUser();
        $this->checkAuth();
    }

    public function checkAuth(){
        if(!isset($this->user)){
            $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
            header('Location: '.$actual_link ."/login");
        }
    }

    /**
     * Route: /customer/
     */
    public function index(){
        $this->render('user/Panel.php',
        [
            'orders' => $this->orderManager->getUserOrders($this->userManager->getSessionUser()->getId()),
        ]);
    }

    public function orderInfo($id){
        $order = $this->orderManager->getOrder($id);
        if($order->getCustomerId() != $this->user->getId()){
            $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
            header('Location: '.$actual_link ."/customer");
        }
        $this->render('user/Order.php',
            [
                'order' => $order,
            ]);
    }
}