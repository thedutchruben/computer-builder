<?php

namespace PcBuilder\Modules\Controllers;

use PcBuilder\Framework\Registery\Controller;

class IndexController extends Controller
{

    /**
     * Route : /
     */
    public function __invoke(){
        $this->render('HomePage.php',['test' => 'welkom op de site']);
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

}