<?php use PcBuilder\Modules\Managers\ComponentManager;
use PcBuilder\Objects\Orders\OrderItems\ConfigrationOrderItem;

$componentManager = new ComponentManager();
include 'templates/AdminHeader.php';
function strbool($value): string
{
    return $value ? 'true' : 'false';
}
?>




<div class="container">
    <div class="row justify-content-md-center">
        <div class="col-12 ">
            <div class="row">
                <div class="col-6">
                    <p>Status : <?php echo $order->getStatus()?></p>
                    <p>Paid : <?php echo strbool($order->isPaid())?></p>
                </div>
                <div class="col-6">
                    <form action="/sdkjnflawsujdnfgpoasdjnfgpoasdng" method="post" class="row g-3">
                        <label>
                            Paid:
                            <input type="checkbox">
                        </label>
                        <label>
                            Status:
                            <select>
                                <option>Ordered</option>
                                <option>In production</option>
                                <option>Send</option>
                            </select>
                        </label>
                        <button>Update</button>
                    </form>
                </div>
            </div>
            <?php foreach ($order->getItems() as $orderItem){?>
                <div class="col-12 ">
                    <h3><?php echo $orderItem->getName() ?></h3>
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
    </div>
</div>