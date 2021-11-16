<?php include 'templates/AdminHeader.php';?>
<script src="/assets/js/ckeditor/ckeditor.js"></script>

<h2>Products</h2>

<table class="table table-striped table-dark">
<thead>
<tr>
    <th scope="col"></th>
    <th scope="col">Name</th>
    <th scope="col">Type</th>
    <th scope="col">Filter :<select id="type" name="type" class="" required>
            <option value="ALL">All</option>
            <option value="CASE">Case</option>
            <option value="CPU">Cpu</option>
            <option value="RAM">Ram</option>
            <option value="MOTHERBOARD">Motherboard</option>
            <option value="GRAPHICSCARD">GraphicsCard</option>
            <option value="STORAGE">Storage</option>
            <option value="DVDPLAYER">Dvdplayer</option>
            <option value="PSU">Psu</option>
            <option value="RGB">RGB</option>
            <option value="OS">OS</option>
        </select></th>
    <th scope="col"><button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addProductModal">
            Add
        </button></th>
</tr>
</thead>
<tbody>

<?php
foreach ($components as $component){
    echo "<tr data-type='".$component->getType()."'>";
    echo "<th class='align-middle' scope='row'><img style='max-height: 4vh;padding-left: 20px' src='".$component->getImage()."'></th>";
    echo "<td class='align-middle'>".$component->getDisplayName()."</td>";
    echo "<td class='align-middle'>".$component->getType()."</td>";
    echo "<td><a class='btn btn-info'>Edit</a></td>";
    echo "<td><a class='btn btn-danger' href='/admin/products/delete/".$component->getId()."'>Delete</a></td>";

    echo "</tr>";
}

?>

</tbody>
</table>


<div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addProduct" class="row" method="post" action="/admin/products/create" enctype="multipart/form-data">
                    <div class="col-12">
                        <label>DisplayName</label>
                        <input type="text" name="name" class="form-control" placeholder="Name" required>
                    </div>
                    <div class="col-12">
                        <label>Description</label>
                        <textarea style="width: 100%" rows="4" cols="50" name="description" form="addProduct"></textarea>
                    </div>
                    <div class="col-12">
                        <label>Image</label>
                        <input type="file" name="image" accept="image/png, image/jpeg" class="form-control">
                    </div>
                    <div class="col-6">
                        <label>Price</label>
                        <input type="number" name="price" class="form-control" placeholder="00.00" step="0.01" required>
                    </div>
                    <div class="col-6">
                        <label>Power need</label>
                        <input type="number" name="power" class="form-control" placeholder="10" step="1" required>
                    </div>
                    <div class="col-6">
                        <label>Type</label>
                        <select id="type" name="type" class="form-control" required>
                            <option value="CASE">Case</option>
                            <option value="CPU">Cpu</option>
                            <option value="RAM">Ram</option>
                            <option value="MOTHERBOARD">Motherboard</option>
                            <option value="GRAPHICSCARD">GraphicsCard</option>
                            <option value="STORAGE">Storage</option>
                            <option value="DVDPLAYER">Dvdplayer</option>
                            <option value="PSU">Psu</option>
                            <option value="RGB">RGB</option>
                            <option value="OS">OS</option>
                        </select>
                    </div>
                    <div class="col-6">
                        <label>Teakers Id</label>
                        <input type="number" name="tweakersid" class="form-control" placeholder="10" step="1" required>
                    </div>
                    <div class="col-6">
                        <label>Enabled</label>
                        <input type="checkbox" name="enabled" class="form-check-input">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" form="addProduct" class="btn btn-primary" >Save</button>
            </div>
        </div>
    </div>
</div>

<script>
    CKEDITOR.replace( 'description' );
</script>