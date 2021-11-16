<?php include 'templates/AdminHeader.php' ?>
<script src="/assets/js/ckeditor/ckeditor.js"></script>
<table class="table table-striped table-dark">
    <thead>
    <tr>
        <th scope="col"></th>
        <th scope="col">Name</th>
        <th scope="col">Price</th>
        <th scope="col"></th>
        <th scope="col"><button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addConfigModal">
                Add
            </button></th>
    </tr>
    </thead>
    <tbody>

    <?php
    foreach ($configs as $config){
        echo "<tr data-type='".$config['name']."'>";
        echo "<th class='align-middle' scope='row'><img style='max-height: 4vh;padding-left: 20px' src='".$config['image']."'></th>";
        echo "<td class='align-middle'>".$config['name']."</td>";
        echo "<td class='align-middle'>".$config['price']."</td>";
        echo "<td><a class='btn btn-info' href='/admin/config/".$config['id']."'>Edit</a></td>";
        echo "<td><a class='btn btn-danger' href='/admin/config/delete/".$config['id']."'>Delete</a></td>";

        echo "</tr>";
    }

    ?>

    </tbody>
</table>


<div class="modal fade" id="addConfigModal" tabindex="-1" aria-labelledby="addConfigModal" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Configurator</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addConfig" class="row" method="post" action="/admin/config/create" enctype="multipart/form-data">
                    <div class="col-12">
                        <label>Name</label>
                        <input type="text" name="name" class="form-control" placeholder="Name" required>
                    </div>
                    <div class="col-12">
                        <label>Description</label>
                        <textarea style="width: 100%" rows="4" cols="50" name="description" id="description" form="addConfig"></textarea>
                    </div>
                    <div class="col-12">
                        <label>Image</label>
                        <input type="file" name="image" accept="image/png, image/jpeg" class="form-control">
                    </div>
                    <div class="col-6">
                        <label>Price</label>
                        <input type="number" name="price" class="form-control" placeholder="00.00" step="0.01" required>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" form="addConfig" class="btn btn-primary" >Save</button>
            </div>
        </div>
    </div>
</div>

<script>
    CKEDITOR.replace( 'description' );
</script>