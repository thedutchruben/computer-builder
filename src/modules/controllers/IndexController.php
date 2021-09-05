<?php

namespace PcBuilder\Modules\Controllers;

use PcBuilder\Framework\Registery\Controller;

class IndexController extends Controller
{

    public function __invoke(){
        $this->render('HomePage.php',['test' => 'welkom op de site']);
    }

}