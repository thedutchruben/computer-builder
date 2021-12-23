<?php include 'templates/AdminHeader.php';
function strbool($value): string
{
    return $value ? 'true' : 'false';
}

?>

<div class="list-group">
    <?php foreach ($orders as $order){ ?>
        <a href="/admin/order/<?php echo $order->getId()?>" class="list-group-item list-group-item-action flex-column align-items-start">
            <div class="d-flex w-100 justify-content-between">
                <h5 class="mb-1">Order <?php echo $order->getId()?></h5>
                <small><?php echo $order->getOrderDate()?></small>
            </div>
            <p class="mb-1">Paid : <?php echo strbool($order->isPaid())?></p>
            <p class="mb-1">Status : <?php echo $order->getStatus()?></p>
            <small>$<?php echo $order->getTotalPrice()?></small>
        </a>

    <?php } ?>
</div>