<?php

namespace PcBuilder\Modules\Controllers;

use PcBuilder\Framework\Registery\Controller;

class AdminController extends Controller
{

    /**
     * Route : /
     */
    public function index(){
        $this->render('\admin\AdminIndex.php');
        $this->flasher_error();
    }

}