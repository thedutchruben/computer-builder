<?php

namespace PcBuilder\Modules\Controllers;

use PcBuilder\Framework\Registery\Controller;
use PcBuilder\Modules\Managers\ConfigurationManager;
use PcBuilder\Modules\Managers\OrderManager;

class IndexController extends Controller
{
    private ConfigurationManager $configruatorManager;
    private OrderManager $orderManager;
    public function __construct()
    {
        parent::__construct();
        $this->configruatorManager = new ConfigurationManager();
        $this->orderManager = new OrderManager();
    }

    /**
     * Route : /
     */
    public function index(){
        $this->render('HomePage.php',[
            'configs' => $this->configruatorManager->getBasicConfigurator(),
        ]);
        $this->flasher_success(
            "<h2>Korting!</h2></br><p>Gebruik nu de code <code>10AF</code> om 10% korting te krijgen op je aankoop</p>",[
                "oneTimeSession" => true
            ]
        );
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

    /**
     * Route : /card
     *
     * This will show the sitemap
     */
    public function card(){
        $this->render('CardPage.php');
    }

}