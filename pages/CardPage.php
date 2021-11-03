<?php

use PcBuilder\Modules\Managers\ConfigruatorManager;
use PcBuilder\Objects\Orders\OrderItems\ConfigrationOrderItem;

$configruatorManager = new ConfigruatorManager();


include 'templates/Header.php';

?>
<div class="container">
    <div class="row justify-content-md-center">
        <div class="col-12 ">
            <ol class="list-group">
            <?php
              foreach (getShoppingCard()->getItems() as $item){
                  echo "<li class='list-group-item'>";
                  echo "<div class='fw-bold' style='font-size: 20px'>". $item->getName() . "</div><br>";
                  if($item instanceof ConfigrationOrderItem){

                      foreach ($item->getComponents() as $component){
                          if($configruatorManager->getComponent($component) != null){
                              echo "<a>- " .$configruatorManager->getComponent($component)->getDisplayName() . "</a><br>";
                          }
                      }
                  }
                  echo "</li>";
              }
            ?>
            </ol>
        </div>
    </div>
</div>
