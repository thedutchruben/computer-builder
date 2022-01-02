<?php

namespace PcBuilder\Modules\Controllers;

use PcBuilder\Framework\Execptions\TemplateNotFound;
use PcBuilder\Framework\Registry\Controller;
use PcBuilder\Modules\Managers\ConfigurationManager;
use PcBuilder\Modules\Managers\OrderManager;
use PcBuilder\Modules\Managers\UserManager;

/**
 * Controller for all the user endpoints
 */
class UserController extends Controller
{

    /**
     * A link to the user manager
     * @var UserManager
     */
    private UserManager $userManager;

    /**
     * Register all the variable's that are needed for the controller
     */
    public function __construct()
    {
        parent::__construct();
        $this->userManager = new UserManager();
    }

    /**
     * Location for the use tot login
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
            if($data['success']){
                if(isset($_POST['gotoPage'])){
                    $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
                    header('Location: '.$actual_link ."/".$_POST['gotoPage']);
                }else{
                    $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
                    header('Location: '.$actual_link ."/customer");
                }

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
     * Location for people to register an account
     * Route : /register
     */
    public function register(){
        if (isset($_POST['email'])) {
            $data = $this->userManager->register($_POST);
            if($data['success']){
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