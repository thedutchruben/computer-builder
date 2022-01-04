<?php

use PcBuilder\Modules\Managers\ComponentManager;
use PcBuilder\Objects\Orders\OrderItems\ConfigurationOrderItem;

include 'templates/UserHeader.php';

$componentManager = new ComponentManager();
$statusStyle = "";

if($order->isPaid()){
    $statusStyle = "paid_status";
    switch ($order->getStatus()){
        case "IN_ORDER":
            $statusStyle = "order_status";
            break;
        case "IN_PRODUCTION":
            $statusStyle = "production_status";
            break;
        case "SEND":
            $statusStyle = "completed_status";
            break;
    }
}

?>

<style>
    .order-data{
        border: 1px solid darkgray;
        border-radius: 10px;
        padding: 20px;
    }

    #order-details{
        padding-bottom: 20px;
    }

    #oder-status{
        text-align: right;
    }

    .paid_status{
        width: 50%;
    }
    .order_status{
        width: 50%;
    }
    .production_status{
        width: 75%;
    }
    .completed_status{
        width: 100%;
    }

    <?php
    if($order->isPaid()){
    switch ($order->getStatus()){
        case "IN_ORDER":
            echo "
                .order_bold{
                    font-weight:bold;
                }
            ";
            break;
        case "IN_PRODUCTION":
            echo "
                .prod_bold{
                    font-weight:bold;
                }
            ";
            break;
        case "SEND":
            echo "
                .comp_bold{
                    font-weight:bold;
                }
            ";
            break;
    }
}
    ?>
</style>

<div class="container order-data">
    <div id="order-details">
        <div class="row">
            <div class="col">
                <h2>Order number : <?php echo $order->getId() ?></h2>
            </div>
            <div class="col">
                <p>Total cost : <?php echo $order->getTotalPrice() ?></p>
                <p>Order date : <?php echo $order->getOrderDate() ?></p>
            </div>
        </div>
    </div>
    <div id="oder-status">
        <div class="container">
            <div class="progress" style="height: 20px;">
                <div class="progress-bar <?php echo $statusStyle?>" role="progressbar"></div>
            </div>
        </div>
        <div class="row">
            <div class="col pay_bold">
                Payment confirm
            </div>
            <div class="col order_bold">
                In order
            </div>
            <div class="col prod_bold">
                In production
            </div>
            <div class="col comp_bold">
                Completed
            </div>
        </div>
    </div>
    <h3>Items:</h3>
    <?php foreach ($order->getItems() as $orderItem){?>
        <div class="col-12 ">
            <h4><?php echo $orderItem->getName() ?></h4>
            <?php if($orderItem instanceof ConfigurationOrderItem){ ?>
                <ul class="list-group">
                    <?php
                    foreach ($orderItem->getComponents() as $component){
                        $componentObject  = $componentManager->getComponent($component);
                        ?>

                        <li class="list-group-item"><?php echo $componentObject->getDisplayName()?></li>

                    <?php } ?>
                </ul>
            <?php } ?>
        </div>
    <?php } ?>

</div>
