<?php

namespace PcBuilder\Modules\Managers;

use PcBuilder\Framework\Registery\Manager;
use Dompdf\Dompdf;
use PcBuilder\Objects\Orders\Order;
use PcBuilder\Objects\Orders\OrderItems\ConfigrationOrderItem;
use PcBuilder\Objects\ShoppingCart;
use PcBuilder\Objects\User\User;

class OrderManager extends Manager
{
    private $componentManager;

    public function __construct()
    {
        parent::__construct();
        $this->componentManager = new ComponentManager();
    }


    public function placeOrder(User $user,array $items){
        if(sizeof($items) == 0) return null;
        $order = new Order(-1,$user->getId(),$items);
        $this->getMysql()->getPdo()->beginTransaction();
        $price = 0.00;
        foreach ($items as $item){
            $price += $item->getPrice();
        }
        try {
            $statement = $this->getMysql()->getPdo()->prepare("INSERT INTO `orders`(`customer_id`, `total_price`) VALUES (:USERID,:PRICE);");
            $statement->execute([
                ":USERID" =>  $user->getId(),
                ":PRICE" =>  $price
            ]);
            $orderId = $this->getMysql()->getPdo()->lastInsertId();
            $order->setId($orderId);
            foreach ($items as $item){
                if($item instanceof ConfigrationOrderItem){
                    $statement = $this->getMysql()->getPdo()->prepare("INSERT INTO `config_item`(`created`) VALUES (CURRENT_DATE);");
                    $statement->execute();
                    $config_item_id = $this->getMysql()->getPdo()->lastInsertId();
                    foreach ($item->getComponents() as $component){
                        $statement = $this->getMysql()->getPdo()->prepare("INSERT INTO `config_item_items`(`id`, `component_type` ,`component_id`) VALUES (:CONFIGID,:COMPONENTTYPE,:COMPONENTID);");
                        $statement->execute([
                            ":CONFIGID" =>  $config_item_id,
                            ":COMPONENTTYPE" => $this->componentManager->getComponent($component)->getType(),
                            ":COMPONENTID" =>  $component
                        ]);
                    }
                    $statement = $this->getMysql()->getPdo()->
                    prepare("INSERT INTO `orders_items`(`id`, `item_id`, `config_id`, `amount`, `price`) VALUES (:ORDERID,null,:CONFIGID,:AMOUNT,:PRICE)");
                    $statement->execute([
                        ":ORDERID" =>  $orderId,
                        ":CONFIGID" =>  $config_item_id,
                        ":AMOUNT" => $item->getAmount(),
                        ":PRICE" => $item->getPrice()
                    ]);
                }
            }
        }catch (\Exception $exception){
            $this->getMysql()->getPdo()->rollBack();
            return null;

        }
        $this->getMysql()->getPdo()->commit();
        return $orderId;

    }


    public function getOpenOrderCount(){
        $statement = $this->getMysql()->getPdo()->
        prepare("SELECT COUNT(`id`) AS Open_Order from `orders` WHERE `status` = 'IN_ORDER'");
        $statement->execute();
        return $statement->fetch()['Open_Order'];
    }

    public function getProductionOrderCount(){
        $statement = $this->getMysql()->getPdo()->
        prepare("SELECT COUNT(`id`) AS Open_Order from `orders` WHERE `status` = 'IN_PRODUCTION'");
        $statement->execute();
        return $statement->fetch()['Open_Order'];
    }

    public function addItemToCart($item){
        $this->flasher_success("Item added to shopping cart");
        //ShoppingCart
        if(!isset($_SESSION['shopping-cart'])){
            $_SESSION['shopping-cart'] = new ShoppingCart();
        }
        $_SESSION['shopping-cart']->addItem($item);
    }

    public function getShoppingCart() :ShoppingCart
    {
        return $_SESSION['shopping-cart'];
    }

    public function getOrders(){

        $items = [];

        try {
            $statement = $this->getMysql()->getPdo()->prepare("SELECT * FROM `orders` ORDER BY `date`");
            $statement->execute();
            foreach ($statement->fetchAll() as $row) {
                $order = new Order($row['id'],$row['customer_id'],[]);
                $order->setTotalPrice($row['total_price']);
                $order->setStatus($row['status']);
                $order->setPaid($this->transferIntToBool($row['paid']));
                $order->setOrderDate($row['date']);
                array_push($items,$order);
            }



        }catch (\Exception $exception){
            $this->flasher_error("Er is iets fout gegaan probeer de pagina te reloaden");
        }

        return $items;
    }

    public function getOrder($id) : ?Order
    {

        try {
            $order = new Order($id,0,[]);
            $statement = $this->getMysql()->getPdo()->prepare("SELECT * FROM `orders` WHERE `id` = :ID");
            $statement->execute([
                ':ID' => $id,
            ]);
            $row = $statement->fetch();
            $order->setCustomerId($row['customer_id']);
            $order->setStatus($row['status']);
            $order->setPaid($this->transferIntToBool($row['paid']));
            $order->setStatus($row['status']);
            $order->setTotalPrice($row['total_price']);
            $order->setOrderDate($row['date']);
            $statement = $this->getMysql()->getPdo()->prepare("SELECT * FROM `orders_items` WHERE `id` = :ID");
            $statement->execute([
                ':ID' => $id,
            ]);
            foreach ($statement->fetchAll() as $row){

            }

            return $order;
        }catch (\Exception $exception){
            $this->flasher_error("Er is iets fout gegaan probeer de pagina te reloaden");
        }
        return null;
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
<p>Date: '.$order->getOrderDate().'</p>
</div>';
        foreach ($this->getShoppingCard()->getItems() as $item){
            $html .= "Name : " . $item['name'] . "<br>";

        }

        $dompdf->loadHtml($html);
        $dompdf->add_info("Title","order");
        $dompdf->render();

        $dompdf->stream("order.pdf", array("Attachment" => $attachment));
    }

    public function transferIntToBool($int) : bool
    {
        return $int == 1 ? true : false;
    }

}