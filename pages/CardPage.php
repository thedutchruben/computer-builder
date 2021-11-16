<?php

use PcBuilder\Modules\Managers\ConfigurationManager;
use PcBuilder\Objects\Orders\OrderItems\ConfigrationOrderItem;

$configruatorManager = new ConfigurationManager();


include 'templates/Header.php';

foreach (getShoppingCard()->getItems() as $item){
    $item->resetPrice();
    foreach ($item->getComponents() as $component){
        $item->addPrice($configruatorManager->getPrice($component));
    }
}

?>
<div class="container">
    <div class="row justify-content-md-center">
        <div class="col-12 ">
            <ol class="list-group">
            <?php
            $total = 0;
              foreach (getShoppingCard()->getItems() as $item){
                  echo "<li class='list-group-item'>";
                  echo "<div class='fw-bold' style='font-size: 20px'>". $item->getName() . "<button type='button' class='btn btn-error'>Remove</button></div><br>";
                  if($item instanceof ConfigrationOrderItem){
                      $total += ($item->getPrice());
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

            <form class="row g-3">
                <?php
                    echo "<p>Total : ".floatval($total)."</p>";
                ?>
                <button>Checkout</button>
            </form>
        </div>
    </div>
</div>
