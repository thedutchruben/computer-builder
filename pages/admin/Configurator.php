<?php include 'templates/AdminHeader.php';
$manager = new \PcBuilder\Modules\Managers\ConfigurationManager();
?>
<div class="container">
    <div class="row">
        <div class="col-md-10 offset-md-1">
            <div class="login-form bg-light mt-4 p-4">
                <form action="/admin/config/<?php echo $config->getId()?>/save" method="post" class="row g-3">
                    <h4><?php echo $config->getName()?></h4>
                    <div class="col-12">
                        <label>Name</label>
                        <input type="text" name="name" class="form-control" placeholder="Name" value="<?php echo $config->getName()?>" required>
                    </div>
                    <?php
                    renderBlock("Case's",$manager->getComponentsByType("CASE"),$config->getCases());
                    renderBlock("Cpu's",$manager->getComponentsByType("CPU"),$config->getCpu());
                    renderBlock("Os",$manager->getComponentsByType("OS"),$config->getOs());
                    renderBlock("Storage",$manager->getComponentsByType("STORAGE"),$config->getStorage());
                    renderBlock("Motherboard",$manager->getComponentsByType("MOTHERBOARD"),$config->getMotherboard());
                    renderBlock("Memory",$manager->getComponentsByType("RAM"),$config->getMemory());
                    renderBlock("Graphics Card",$manager->getComponentsByType("GRAPHICSCARD"),$config->getGpu());
                    renderBlock("Rgb",$manager->getComponentsByType("RGB"),$config->getRgb());
                    renderBlock("Psu",$manager->getComponentsByType("PSU"),$config->getPsu());

                    ?>
                    <hr class="mt-4">
                    <button type="submit" class="btn btn-primary" >Save</button>
            </div>
        </div>
    </div>
</div>


<?php

function isInConfig($id,$configItems) :bool
{
    return in_array($id,$configItems);
}

function renderBlock($title,$items,$configItems){
    echo "<div class='col-6'>";
    echo "<label>".$title."</label>";
    echo "<div style='max-height: 250px;overflow: scroll'>";
    foreach ($items as $item){
        echo "<div class='form-check form-switch'>";
        echo "<label class='form-check-label'>".$item->getDisplayName()."</label>";
        if(isInConfig($item->getId(),$configItems)){
            echo "<input class='form-check-input' name='component[".$item->getId()."]' value='".$item->getId()."' type='checkbox' checked>";
        }else{
            echo "<input class='form-check-input' name='component[".$item->getId()."]' value='".$item->getId()."' type='checkbox'>";
        }
        echo "</div>";
    }
    echo "</div>";
    echo "</div>";
}

?>
