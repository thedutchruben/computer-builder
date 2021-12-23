<?php include 'templates/AdminHeader.php';
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
                    <form action="/login" method="post" class="row g-3">
                        <label>
                            Paid:
                            <input type="checkbox">
                        </label>
                        <label>
                            Paid:
                            <select>
                                <option>Ordered</option>
                                <option>In production</option>
                                <option>Send</option>
                            </select>
                        </label>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>