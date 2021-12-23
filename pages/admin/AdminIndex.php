<?php include 'templates/AdminHeader.php' ?>

<!--<style>-->
<!--    .card{-->
<!--        space: 10px;-->
<!--    }-->
<!--</style>-->

<div style="padding:20px" class="card-group">
    <div class="card text-white bg-success mb-3 justify-content-md-center" style="max-width: 10rem;">
        <div class="card-header">Open Orders</div>
        <div class="card-body">
            <p class="card-text"><?php echo $openOrders ?></p>
        </div>
    </div>

    <div class="card text-white bg-success mb-3 justify-content-md-center" style="max-width: 10rem;">
        <div class="card-header">In Prodution</div>
        <div class="card-body">
            <p class="card-text"><?php echo $productionOrders ?></p>
        </div>
    </div>

    <div class="card text-white bg-warning mb-3 justify-content-md-center" style="max-width: 10rem;">
        <div class="card-header">Wrong Prices <i data-bs-toggle="modal" data-bs-target="#wrongPrice" class="fas fa-info-circle"></i></div>
        <div class="card-body">
            <p class="card-text"><?php echo sizeof($wrongPrice)?></p>
        </div>
    </div>
</div>

<div class="modal fade" id="wrongPrice" tabindex="-1" aria-labelledby="wrongPrice" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Wrong Prices</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-dark">
                    <thead>
                    <tr>
                        <th scope="col">Name</th>
                        <th scope="col">Price</th>
                        <th scope="col">Marketing Price</th>
                        <th scope="col"></th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php
                    foreach ($wrongPrice as $wrong){
                        echo "<tr data-type=''>";
                        echo "<th class='align-middle' scope='row'>".$wrong['name']."</th>";
                        echo "<td class='align-middle'>".$wrong['price']."</td>";
                        echo "<td class='align-middle'>".$wrong['currentPrice']."</td>";
                        echo "<td><a class='btn btn-danger' href='/admin/products/".$wrong['id']."/edit'>Edit</a></td>";

                        echo "</tr>";
                    }

                    ?>

                    </tbody>
                </table>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>