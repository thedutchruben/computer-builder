<?php

use PcBuilder\Modules\Managers\ComponentManager;
use PcBuilder\Modules\Managers\ConfigurationManager;
use PcBuilder\Objects\Orders\OrderItems\ConfigurationOrderItem;

$configruatorManager = new ConfigurationManager();
$componentManager = new ComponentManager();


include 'templates/Header.php';

foreach (getShoppingCart()->getItems() as $item){
    $item->resetPrice();
    foreach ($item->getComponents() as $component){
        $item->addPrice($componentManager->getPrice($component));
    }
}

?>
<div class="container">
    <div class="row justify-content-md-center">
        <div class="col-12 ">
            <ol class="list-group">
            <?php
            $total = 0;
              foreach (getShoppingCart()->getItems() as $item){
                  echo "<li class='list-group-item'>";
                  echo "<div class='fw-bold' style='font-size: 20px'>". $item->getName() . "<button type='button' class='btn btn-error'>Remove</button></div><br>";
                  if($item instanceof ConfigurationOrderItem){
                      $total += ($item->getPrice());
                      foreach ($item->getComponents() as $component){
                          if($componentManager->getComponent($component) != null){
                              echo "<a>- " .$componentManager->getComponent($component)->getDisplayName() . "</a><br>";
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
                <a class="btn btn-success" href="/checkout/">Checkout</a>
            </form>
        </div>
    </div>
</div>
