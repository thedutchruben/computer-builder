<?php

namespace PcBuilder\Modules\Managers;

use PcBuilder\Framework\Registery\Manager;
use Dompdf\Dompdf;
use PcBuilder\Objects\Orders\Order;
use PcBuilder\Objects\ShoppingCard;
use PcBuilder\Utils\CastUtil;

class OrderManager extends Manager
{

    public function addItemToCard($item){
        $this->flasher_success("Item added to shopping card");
        //ShoppingCard
        if(!isset($_SESSION['shopping-card'])){
            $_SESSION['shopping-card'] = new ShoppingCard();
        }
        $_SESSION['shopping-card']->addItem($item);
    }

    public function getShoppingCard() :ShoppingCard
    {
        return $_SESSION['shopping-card'];
    }

    /**
     *  Render the pdf of the order
     * @param Order $order
     * @param bool $attachment
     */
    public function renderPDF(Order $order,bool $attachment = false){

        $dompdf = new Dompdf();
        $html = '<div id="header">
<h1>Order :oderId</h1>
<p>Date: 24-09-2021</p>
</div>';
        foreach ($this->getShoppingCard()->getItems() as $item){
            $html .= "Name : " . $item['name'] . "<br>";

        }
//        foreach ($components as $component){
//        }
        $dompdf->loadHtml($html);
        $dompdf->add_info("Title","order");
        $dompdf->render();

        $dompdf->stream("order.pdf", array("Attachment" => $attachment));
    }
}