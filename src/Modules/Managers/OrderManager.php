<?php

namespace PcBuilder\Modules\Managers;

use PcBuilder\Framework\Registery\Manager;
use Dompdf\Dompdf;
use PcBuilder\MailUtill;
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

        $mail = new MailUtill('Order placed','PCBuilder');
        $mail->getMessage()->addPart('
                <!DOCTYPE html>

<html lang="en" xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:v="urn:schemas-microsoft-com:vml">
<head>
<title></title>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<!--[if mso]><xml><o:OfficeDocumentSettings><o:PixelsPerInch>96</o:PixelsPerInch><o:AllowPNG/></o:OfficeDocumentSettings></xml><![endif]-->
<style>
		* {
			box-sizing: border-box;
		}

		body {
			margin: 0;
			padding: 0;
		}

		a[x-apple-data-detectors] {
			color: inherit !important;
			text-decoration: inherit !important;
		}

		#MessageViewBody a {
			color: inherit;
			text-decoration: none;
		}

		p {
			line-height: inherit
		}

		@media (max-width:520px) {
			.icons-inner {
				text-align: center;
			}

			.icons-inner td {
				margin: 0 auto;
			}

			.row-content {
				width: 100% !important;
			}

			.stack .column {
				width: 100%;
				display: block;
			}
		}
	</style>
</head>
<body style="background-color: #FFFFFF; margin: 0; padding: 0; -webkit-text-size-adjust: none; text-size-adjust: none;">
<table border="0" cellpadding="0" cellspacing="0" class="nl-container" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #FFFFFF;" width="100%">
<tbody>
<tr>
<td>
<table align="center" border="0" cellpadding="0" cellspacing="0" class="row row-1" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
<tbody>
<tr>
<td>
<table align="center" border="0" cellpadding="0" cellspacing="0" class="row-content stack" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; color: #000000; width: 500px;" width="500">
<tbody>
<tr>
<td class="column" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; padding-top: 5px; padding-bottom: 5px; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;" width="100%">
<table border="0" cellpadding="0" cellspacing="0" class="heading_block" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
<tr>
<td style="width:100%;text-align:center;">
<h1 style="margin: 0; color: #555555; font-size: 23px; font-family: Arial, Helvetica Neue, Helvetica, sans-serif; line-height: 120%; text-align: center; direction: ltr; font-weight: normal; letter-spacing: normal; margin-top: 0; margin-bottom: 0;">{title}</h1>
</td>
</tr>
</table>
<table border="0" cellpadding="10" cellspacing="0" class="text_block" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;" width="100%">
<tr>
<td>
<div style="font-family: sans-serif">
<div style="font-size: 12px; mso-line-height-alt: 14.399999999999999px; color: #555555; line-height: 1.2; font-family: Arial, Helvetica Neue, Helvetica, sans-serif;">
<p style="margin: 0; font-size: 12px;">{text}</p>
</div>
</div>
</td>
</tr>
</table>
<table border="0" cellpadding="0" cellspacing="0" class="image_block" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
<tr>
<td style="width:100%;padding-right:0px;padding-left:0px;">
<div align="center" style="line-height:10px"><img src="" style="display: block; height: auto; border: 0; width: 100px; max-width: 100%;" width="100"/></div>
</td>
</tr>
</table>
<table border="0" cellpadding="10" cellspacing="0" class="divider_block" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
<tr>
<td>
<div align="center">
<table border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
<tr>
<td class="divider_inner" style="font-size: 1px; line-height: 1px; border-top: 1px solid #BBBBBB;"><span> </span></td>
</tr>
</table>
</div>
</td>
</tr>
</table>
<table border="0" cellpadding="10" cellspacing="0" class="social_block" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
<tr>
<td>
<table align="center" border="0" cellpadding="0" cellspacing="0" class="social-table" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="144px">
<tr>
<td style="padding:0 2px 0 2px;"><a href="https://www.facebook.com/" target="_blank"><img alt="Facebook" height="32" src="images/facebook2x.png" style="display: block; height: auto; border: 0;" title="Facebook" width="32"/></a></td>
<td style="padding:0 2px 0 2px;"><a href="https://twitter.com/" target="_blank"><img alt="Twitter" height="32" src="images/twitter2x.png" style="display: block; height: auto; border: 0;" title="Twitter" width="32"/></a></td>
<td style="padding:0 2px 0 2px;"><a href="https://instagram.com/" target="_blank"><img alt="Instagram" height="32" src="images/instagram2x.png" style="display: block; height: auto; border: 0;" title="Instagram" width="32"/></a></td>
<td style="padding:0 2px 0 2px;"><a href="https://www.linkedin.com/" target="_blank"><img alt="LinkedIn" height="32" src="images/linkedin2x.png" style="display: block; height: auto; border: 0;" title="LinkedIn" width="32"/></a></td>
</tr>
</table>
</td>
</tr>
</table>
</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
<table align="center" border="0" cellpadding="0" cellspacing="0" class="row row-2" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
<tbody>
<tr>
<td>
<table align="center" border="0" cellpadding="0" cellspacing="0" class="row-content stack" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; color: #000000; width: 500px;" width="500">
<tbody>
<tr>
<td class="column" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; padding-top: 5px; padding-bottom: 5px; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;" width="100%">
<table border="0" cellpadding="0" cellspacing="0" class="icons_block" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
<tr>
<td style="color:#9d9d9d;font-family:inherit;font-size:15px;padding-bottom:5px;padding-top:5px;text-align:center;">
<table cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
<tr>
<td style="text-align:center;">
<!--[if vml]><table align="left" cellpadding="0" cellspacing="0" role="presentation" style="display:inline-block;padding-left:0px;padding-right:0px;mso-table-lspace: 0pt;mso-table-rspace: 0pt;"><![endif]-->
<!--[if !vml]><!-->
<table cellpadding="0" cellspacing="0" class="icons-inner" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; display: inline-block; margin-right: -4px; padding-left: 0px; padding-right: 0px;">
<!--<![endif]-->
<tr>
</tr>
</table>
</td>
</tr>
</table>
</td>
</tr>
</table>
</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
</body>
</html>
        ','text/html');

        $mail->send($user->getEmail());
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