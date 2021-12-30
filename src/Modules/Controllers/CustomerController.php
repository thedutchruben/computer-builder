<?php

namespace PcBuilder\Modules\Controllers;

use PcBuilder\Framework\Registery\Controller;
use PcBuilder\Modules\Managers\OrderManager;
use PcBuilder\Modules\Managers\UserManager;

class CustomerController extends Controller
{
    private OrderManager $orderManager;
    private UserManager $userManager;

    public function __construct()
    {
        parent::__construct();
        $this->orderManager = new OrderManager();
        $this->userManager = new UserManager();
    }


    /**
     * Route: /customer/
     */
    public function index(){
        $this->render('user/Panel.php',
        [
            'orders' => $this->orderManager->getUserOrders( $this->userManager->getSessionUser()->getId()),
        ]);
    }
}