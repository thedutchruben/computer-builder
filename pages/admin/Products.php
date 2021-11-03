<?php include 'templates/AdminHeader.php';?>


<ul class="list-group">

    <?php
        foreach ($components as $component){
            echo "<li class='list-group-item disabled'>".$component->getDisplayName()."</li>";
        }

    ?>

</ul>