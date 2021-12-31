<?php
include 'templates/UserHeader.php';

function getStatusFormat($order){
    $status = "Waiting for confirm";

    if($order->isPaid()){
        $status = "Payment completed";
        switch ($order->getStatus()){
            case "IN_ORDER":
                $status = "In order";
                break;
            case "IN_PRODUCTION":
                $status = "In production";
                break;
            case "SEND":
                $status = "Completed";
                break;
        }
    }

    return $status;
}
?>


<div class="container">
    <div class="row">
        <div id="orders" class="col">
            <h3>Orders</h3>
            <table class="table table-hover">
                <thead class="thead-dark">
                <tr>
                    <th scope="col">Invoice</th>
                    <th scope="col">Date</th>
                    <th scope="col">Status</th>
                    <th scope="col"></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($orders as $order){ ?>
                <tr>
                    <th scope="row"><?php echo $order->getId() ?></th>
                    <td><?php echo $order->getOrderDate() ?></td>
                    <td><?php echo getStatusFormat($order) ?></td>
                    <td><a href="/customer/order/<?php echo $order->getId() ?>">Open</a></td>
                </tr>
                <?php } ?>
            </table>

        </div>
        <div class="col">

        </div>
    </div>

</div>