<?php

use PcBuilder\Modules\Managers\ComponentManager;
use PcBuilder\Objects\Orders\OrderItems\ConfigrationOrderItem;

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
        width: 25%;
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
            <div class="col">
                Payment confirm
            </div>
            <div class="col">
                In order
            </div>
            <div class="col">
                <b>In production</b>
            </div>
            <div class="col">
                Completed
            </div>
        </div>
    </div>
    <h3>Items:</h3>
    <?php foreach ($order->getItems() as $orderItem){?>
        <div class="col-12 ">
            <h4><?php echo $orderItem->getName() ?></h4>
            <?php if($orderItem instanceof ConfigrationOrderItem){ ?>
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
