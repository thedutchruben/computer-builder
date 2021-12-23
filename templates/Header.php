<?php

use PcBuilder\Modules\Managers\UserManager;
use PcBuilder\Objects\Orders\OrderItems\ConfigrationOrderItem;
use PcBuilder\Objects\ShoppingCart;

$userManager = new UserManager();
function addItemToCart($item){
    //ShoppingCart
    if(!isset($_SESSION['shopping-cart'])){
        $_SESSION['shopping-cart'] = new ShoppingCart();
    }
    $_SESSION['shopping-cart']->addItem($item);
}

function getShoppingCart() :ShoppingCart
{
    if(!isset($_SESSION['shopping-cart'])){
        $_SESSION['shopping-cart'] = new ShoppingCart();
    }
    return $_SESSION['shopping-cart'];
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <link rel="apple-touch-icon" sizes="180x180" href="/assets/img/faicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/assets/img/faicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/assets/img/faicon/favicon-16x16.png">
    <link rel="manifest" href="/assets/img/faicon/site.webmanifest">
    <link rel="mask-icon" href="/assets/img/faicon/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PcBuilder Site</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="/assets/js/flash.min.js"></script>
    <script src="https://kit.fontawesome.com/2baef102dd.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="/assets/css/base.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/header.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/flash.min.css">

</head>
<body>
<header class="bg-dark">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a href="/">
                <img class="navbar-brand"  src="/assets/img/logo/logo_white_large.png" height="auto" width="280">
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo02" aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarTogglerDemo02">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="/">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/pc-configs">Samenstellingen</a>
                    </li>
                </ul>

                <button type="button" class="btn btn-outline-success" style="margin-left: 10px" data-bs-toggle="modal" data-bs-target="#shoppingCardModal">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cart" viewBox="0 0 16 16">
                        <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM3.102 4l1.313 7h8.17l1.313-7H3.102zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"></path>
                    </svg>
                    Cart(<?php echo sizeof(getShoppingCart()->getItems())?>)
                </button>
                <?php if($userManager->is_authenticated()){ ?>
                    <a style="margin-left: 10px;" class="btn btn-outline-info" href="/customer">Mijn account</a>
                <?php }else{ ?>
                    <a style="margin-left: 10px;" class="btn btn-outline-info" href="/login">Login</a>
                <?php } ?>
            </div>
        </div>
    </nav>

</header>
<div class="modal fade" id="shoppingCardModal" tabindex="-1" role="dialog" aria-labelledby="shoppingCardModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Card</h5>
                <button type="button" class="close" data-bs-toggle="modal" data-bs-target="#shoppingCardModal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php
                    foreach (getShoppingCart()->getItems() as $item){
                        echo $item->getName();
                        echo $item instanceof ConfigrationOrderItem;
                        if($item instanceof ConfigrationOrderItem){
//                            foreach ($item->getComponents() as $component){
//                                echo $component . "<br>";
//                            }
                        }
                    }
                ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#shoppingCardModal">Verder Winkelen</button>
                <a type="button" class="btn btn-primary" href="/card">Afronden</a>
            </div>
        </div>
    </div>
</div>