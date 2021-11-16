<?php

namespace PcBuilder\Modules\Controllers;

use PcBuilder\Framework\Execptions\TemplateNotFound;
use PcBuilder\Framework\Registery\Controller;
use PcBuilder\Modules\Managers\ConfigurationManager;
use PcBuilder\Modules\Managers\OrderManager;
use PcBuilder\Modules\Managers\UserManager;

class UserController extends Controller
{

    private UserManager $userManager;
    private CustomerController $customerController;

    public function __construct()
    {
        parent::__construct();
        $this->userManager = new UserManager();
        $this->customerController = new CustomerController();
    }

    /**
     * Route : /login
     * @throws TemplateNotFound
     */
    public function login(){
        if($this->userManager->is_authenticated()){
            $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
            header('Location: '.$actual_link ."/customer");
            return;
        }
        if (isset($_POST['password'])) {
            $data = $this->userManager->login($_POST);
            if($data['succes']){
                $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
                header('Location: '.$actual_link ."/customer");
            }else{
                $this->render("\account\Login.php");
                $this->flasher_error(
                    "<h3>Login Failure</h3><br><p>".$data['message']."</p>"
                );
            }
        } else  {
            $this->render("\account\Login.php");
        }
    }

    /**
     * Route : /register
     */
    public function register(){
        if (isset($_POST['email'])) {
            $data = $this->userManager->register($_POST);
            if($data['succes']){
                $this->login();
            }else{
                $this->render("\account\Register.php");
                $this->flasher_error(
                    "<h3>Register Failure</h3><br><p>".$data['message']."</p>"
                );
            }
        } else  {
            $this->render("\account\Register.php");
        }
    }

}