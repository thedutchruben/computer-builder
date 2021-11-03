<?php

namespace PcBuilder\Modules\Controllers;

use PcBuilder\Framework\Registery\Controller;
use PcBuilder\Modules\Managers\ConfigruatorManager;
use PcBuilder\Modules\Managers\UserManager;

class AdminController extends Controller
{
    private UserManager $userManager;
    private ConfigruatorManager $configruatorManager;


    public function __construct()
    {
        parent::__construct();
        $this->userManager = new UserManager();
        $this->configruatorManager = new ConfigruatorManager();
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
            "components" => $this->configruatorManager->getComponents(),
        ]);
    }

}