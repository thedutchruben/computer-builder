<?php

namespace PcBuilder\Modules\Managers;

use PcBuilder\Framework\Registry\Manager;
use PcBuilder\MailUtil;
use PcBuilder\Objects\Orders\Order;
use PcBuilder\Objects\Orders\OrderItem;
use PcBuilder\Objects\Orders\OrderItems\ConfigurationOrderItem;
use PcBuilder\Objects\ShoppingCart;
use PcBuilder\Objects\User\User;

/**
 * Manage all the orders
 */
class OrderManager extends Manager
{

    /**
     * A link to the component manager
     * @var ComponentManager
     */
    private ComponentManager $componentManager;

    /**
     * Construct the manager and load all the classes that are needed
     */
    public function __construct()
    {
        parent::__construct();
        $this->componentManager = new ComponentManager();
    }


    /**
     * Place an order
     * @param User $user
     * @param array $items
     * @return false|string|null
     */
    public function placeOrder(User $user, array $items): bool|string|null
    {
        if(sizeof($items) == 0) return null;
        $order = new Order(-1,$user->getId(),$items);
        $this->getMysql()->getPdo()->beginTransaction();
        $price = 0.00;

        //Calculate the price
        foreach ($items as $item){
            $price += $item->getPrice();
        }

        try {
            //Create the order in the database
            $statement = $this->getMysql()->getPdo()->prepare("INSERT INTO `orders`(`customer_id`, `total_price`) VALUES (:USERID,:PRICE);");
            $statement->execute([
                ":USERID" =>  $user->getId(),
                ":PRICE" =>  $price
            ]);
            $orderId = $this->getMysql()->getPdo()->lastInsertId();
            $order->setId($orderId);

            //Put the order items in the database
            foreach ($items as $item){
                if($item instanceof ConfigurationOrderItem){
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


    /**
     * Update the order
     * @param Order $order
     * @return void
     */
    public function updateOrder(Order $order){
        $statement = $this->getMysql()->getPdo()->prepare("UPDATE `orders` SET `status`=:STATUS,`paid`=:PAID WHERE `id` = :ORDERID");
        $statement->execute([
            ":ORDERID" =>  $order->getId(),
            ":STATUS" =>  $order->getStatus(),
            ":PAID" => $order->isPaid()
        ]);
    }

    /**
     * Get a count with open orders
     * @return mixed
     */
    public function getOpenOrderCount(){
        $statement = $this->getMysql()->getPdo()->
        prepare("SELECT COUNT(`id`) AS Open_Order from `orders` WHERE `status` = 'IN_ORDER'");
        $statement->execute();
        return $statement->fetch()['Open_Order'];
    }

    /**
     * Get a count with orders that are in production
     * @return mixed
     */
    public function getProductionOrderCount(){
        $statement = $this->getMysql()->getPdo()->
        prepare("SELECT COUNT(`id`) AS Open_Order from `orders` WHERE `status` = 'IN_PRODUCTION'");
        $statement->execute();
        return $statement->fetch()['Open_Order'];
    }

    /**
     * Add an item to the shopping cart
     * @param OrderItem $item
     * @return void
     */
    public function addItemToCart(OrderItem $item){
        $this->flasher_success("Item added to shopping cart",[
            'showTill' => microtime(true) + 5000
        ]);
        //ShoppingCart
        if(!isset($_SESSION['shopping-cart'])){
            $_SESSION['shopping-cart'] = new ShoppingCart();
        }
        $_SESSION['shopping-cart']->addItem($item);
    }

    /**
     * Get the shopping cart
     * @return ShoppingCart
     */
    public function getShoppingCart() :ShoppingCart
    {
        return $_SESSION['shopping-cart'];
    }

    /**
     * Get all the order by newest date
     * @return array
     */
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
            $this->flasher_error("Er is iets fout gegaan probeer de pagina te reloaden",
                [
                    'showTill' => microtime(true) + 1000
                ]);
        }

        return $items;
    }

    /**
     * Get an order by id
     * @param int $id
     * @return Order|null
     */
    public function getOrder(int $id) : ?Order
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
                    $configItem = new ConfigurationOrderItem($config_item['name'],$amount,[]);
                    $statement = $this->getMysql()->getPdo()->prepare("SELECT * FROM `config_item_items` WHERE `id` = :ID");
                    $statement->execute([
                        ':ID' => $row['config_id'],
                    ]);

                    foreach ($statement->fetchAll() as $item){
                        $array = $configItem->getComponents();
                        array_push($array,$item['component_id']);
                        $configItem->setComponents($array);
                    }
                    $configItem->addPrice($price);
                    $array1 = $order->getItems();
                    array_push($array1,$configItem);
                    $order->setItems($array1);
                }
            }

            return $order;
        }catch (\Exception $exception){
            $this->flasher_error("Something went wrong try to refresh",
                [
                    'showTill' => microtime(true) + 1000
                ]);
        }
        return null;
    }

    /**
     * Transfer the int from mysql to a bool in php
     * @param int $int
     * @return bool
     */
    public function transferIntToBool(int $int = 0) : bool
    {
        return $int == 1 ? true : false;
    }

    /**
     * Get the orders of a user
     * @param int $userId
     * @return array
     */
    public function getUserOrders(int $userId) : array
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