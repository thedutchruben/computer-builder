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
                <div class="col-3">
                    <p>Name : <?php echo $customer->getUserName() ?></p>
                    <p>Email : <?php echo $customer->getEmail() ?></p>
                    <p>Phone : <?php echo $customer->getPhoneNumber(); ?></p>
                    <p>Addres :</p>
                    <p> <?php echo $customer->getStreet() ?></p>
                    <p> <?php echo $customer->getZipcode() ?> <?php echo $customer->getCity() ?></p>
                    <p> <?php echo $customer->getState() ?> <?php echo $customer->getCountry() ?></p>

                </div>
                <div class="col-3">
                    <p>Status : <?php echo $order->getStatus()?></p>
                    <p>Paid : <?php echo strbool($order->isPaid())?></p>
                </div>
                <div class="col-6">
                    <form action="/admin/order/<?php echo $order->getId()?>/update" method="POST" class="row g-3">
                        <label>
                            Paid:

                            <input id="paid" name="paid" type="checkbox" <?php if($order->isPaid()) echo "checked"?>>
                        </label>
                        <label>
                            Status:
                            <select id="status" name="status">
                                <option value="<?php echo $order->getStatus()?>"><?php echo $order->getStatus()?> (Current)</option>
                                <option value="IN_ORDER">Ordered</option>
                                <option value="IN_PRODUCTION">In production</option>
                                <option value="SEND">Send</option>
                            </select>
                        </label>
                        <label>
                            Update Customer
                            <input id="update_customer" name="update_customer" type="checkbox" checked>
                        </label>
                        <button type="submit">Update</button>
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