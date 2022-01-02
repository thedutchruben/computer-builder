<?php

namespace PcBuilder\Modules\Controllers;

use PcBuilder\Framework\Execptions\TemplateNotFound;
use PcBuilder\Framework\Registery\Controller;
use PcBuilder\Modules\Managers\OrderManager;
use PcBuilder\Modules\Managers\UserManager;
use PcBuilder\Objects\User\User;

/**
 *
 */
class CustomerController extends Controller
{
    /**
     * Link to the order manager
     * @var OrderManager
     */
    private OrderManager $orderManager;
    /**
     * Link to the user manager
     * @var UserManager
     */
    private UserManager $userManager;
    /**
     * The user that has been connected
     * @var User|null
     */
    private ?User $user;

    /**
     * Construct and get all the data that is needed for the pages
     */
    public function __construct()
    {
        parent::__construct();
        $this->orderManager = new OrderManager();
        $this->userManager = new UserManager();
        $this->user = $this->userManager->getSessionUser();
        $this->checkAuth();
    }

    /**
     * Check if the user is authenticated
     * if not the user will get send to the login page
     * @return void
     */
    public function checkAuth(){
        if(!isset($this->user)){
            $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
            header('Location: '.$actual_link ."/login");
        }
    }

    /**
     * Get the main page for the user
     * Route: /customer/
     */
    public function index(){
        $this->render('user/Panel.php',
        [
            'orders' => $this->orderManager->getUserOrders($this->userManager->getSessionUser()->getId()),
        ]);
    }

    /**
     * Get the order info of a users order
     * @param int $id
     * @return void
     * @throws TemplateNotFound
     */
    public function orderInfo(int $id){
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