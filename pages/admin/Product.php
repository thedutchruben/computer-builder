<?php include 'templates/AdminHeader.php'

?>
<script src="https://cdn.tiny.cloud/1/6j1opp2yddudbj6wt1jh2607ehxrwywtlxb5ueicuymojyrl/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script
>



<div class="container">
    <div class="row justify-content-md-center">
        <h2><?php echo $component->getDisplayName();?></h2>
        <p>Current Price: <?php echo $currentPrice ?></p>
        <div class="col-12 ">
            <form id="updateProduct" class="row" method="post" action="/admin/products/update" enctype="multipart/form-data">
                <input type="number" name="id" class="form-control" placeholder="0" required value="<?php echo $component->getId();?>" hidden>
                <div class="col-12">
                    <label>DisplayName</label>
                    <input type="text" name="name" class="form-control" placeholder="Name" required value="<?php echo $component->getDisplayName();?>">
                </div>
                <div class="col-12">
                    <label>Description</label>
                    <textarea style="width: 100%" rows="4" cols="50" name="description" form="updateProduct"><?php echo $component->getDescription();?></textarea>
                </div>
                <div class="col-12">
                    <label>Image</label>
                    <input type="text" name="image" accept="image/png, image/jpeg" class="form-control" value="<?php echo $component->getRawImage();?>">
                </div>
                <div class="col-6">
                    <label>Price</label>
                    <input type="number" name="price" class="form-control" placeholder="00.00" step="0.01" value="<?php echo $component->getPrice();?>" required>
                </div>
                <div class="col-6">
                    <label>Power need</label>
                    <input type="number" name="power" class="form-control" placeholder="10" step="1" value="<?php echo $component->getPowerNeed();?>" required>
                </div>
                <div class="col-6">
                    <label>Type</label>
                    <select id="type" name="type" class="form-control" required>
                        <?php echo "<option value='".$component->getType()."' selected>".$component->getType()."</option>" ?>
                        <option value="CASE" >Case</option>
                        <option value="CPU">Cpu</option>
                        <option value="CPUCOOLER">CPUCOOLER</option>
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
                    <label>Megekko Id</label>
                    <input type="number" name="tweakersid" class="form-control" placeholder="10" step="1" value="<?php echo $component->getTweakersId();?>" required>
                </div>
                <div class="col-6">
                    <label>Enabled</label>
                    <?php if($component->isEnabled()){
                        echo "<input type='checkbox' name='enabled' class='form-check-input' checked>";
                    }else{
                        echo "<input type='checkbox' name='enabled' class='form-check-input'>";
                    }
                    ?>

                </div>
            </form>

            <div class="modal-footer">
                <a type="button" class="btn btn-secondary" href="/admin/products">Close</a>
                <button type="submit" form="updateProduct" class="btn btn-primary" >Save</button>
            </div>
        </div>
    </div>
</div>

<script>
    tinymce.init({
        selector: 'textarea',
        plugins: 'advlist autolink lists link image charmap print preview hr anchor pagebreak',
        toolbar_mode: 'floating',
    });
</script>