<?php

namespace PcBuilder\Modules\Managers;

use PcBuilder\Framework\Registery\Manager;
use PcBuilder\MailUtil;
use PcBuilder\Objects\Orders\Order;
use PcBuilder\Objects\Orders\OrderItems\ConfigrationOrderItem;
use PcBuilder\Objects\ShoppingCart;
use PcBuilder\Objects\User\User;

class OrderManager extends Manager
{
    private ComponentManager $componentManager;

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
                    $statement = $this->getMysql()->getPdo()->prepare("INSERT INTO `config_item`(`created`,`name`) VALUES (CURRENT_DATE,:CONFIGNAME);");
                    $statement->execute([
                        ":CONFIGNAME" => $item->getName()
                    ]);
                    $config_item_id = $this->getMysql()->getPdo()->lastInsertId();
                    foreach ($item->getComponents() as $component){
                        $statement = $this->getMysql()->getPdo()->prepare("INSERT INTO `config_item_items`(`id`, `component_type` ,`component_id`) VALUES (:CONFIGID,:COMPONENTTYPE,:COMPONENTID);");
                        $componentObject = $this->componentManager->getComponent($component);
                        if($componentObject != null){
                            $statement->execute([
                                ":CONFIGID" =>  $config_item_id,
                                ":COMPONENTTYPE" => $componentObject->getType(),
                                ":COMPONENTID" =>  $component
                            ]);
                        }

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

        $mail = new MailUtil('Order placed','PCBuilder');
        $mail->getMessage()->addPart(file_get_contents($_SERVER['DOCUMENT_ROOT']  . "\pages\mails\status\OrderPlacedMail.html"),'text/html');
        $mail->send($user->getEmail());

        $this->getShoppingCart()->clearItems();
        return $orderId;

    }


    public function updateOrder(Order $order){
        $statement = $this->getMysql()->getPdo()->prepare("UPDATE `orders` SET `status`=:STATUS,`paid`=:PAID WHERE `id` = :ORDERID");
        $statement->execute([
            ":ORDERID" =>  $order->getId(),
            ":STATUS" =>  $order->getStatus(),
            ":PAID" => $order->isPaid()
        ]);
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
            $statement = $this->getMysql()->getPdo()->prepare("SELECT * FROM `orders` ORDER BY `date` DESC");
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
                $price = $row['price'];
                $amount = $row['amount'];

                if(isset($row['config_id'])){
                    $statement = $this->getMysql()->getPdo()->prepare("SELECT * FROM `config_item` WHERE `id` = :ID");
                    $statement->execute([
                        ":ID" => $row['config_id'],
                    ]);
                    $config_item = $statement->fetch();
                    $configItem = new ConfigrationOrderItem($config_item['name'],$amount,[]);
                    $statement = $this->getMysql()->getPdo()->prepare("SELECT * FROM `config_item_items` WHERE `id` = :ID");
                    $statement->execute([
                        ':ID' => $row['config_id'],
                    ]);

                    foreach ($statement->fetchAll() as $item){
                        $array = $configItem->getComponents();
                        array_push($array,$item['component_id']);
                        $configItem->setComponents($array);
                    }

                    $array1 = $order->getItems();
                    array_push($array1,$configItem);
                    $order->setItems($array1);
                }
            }

            return $order;
        }catch (\Exception $exception){
            $this->flasher_error("Er is iets fout gegaan probeer de pagina te reloaden");
        }
        return null;
    }

    public function transferIntToBool($int) : bool
    {
        return $int == 1 ? true : false;
    }

    public function getUserOrders($userId) : array
    {
        $statement =  $this->getMysql()->getPdo()->prepare("SELECT `id` FROM `orders` WHERE `customer_id` = :ID");
        $statement->execute([
            ':ID' => $userId,
        ]);
        $orders = array();
        foreach ($statement->fetchAll() as $row){
            array_push($orders,$this->getOrder($row['id']));
        }

        return $orders;
    }

}