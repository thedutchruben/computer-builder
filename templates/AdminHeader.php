<!DOCTYPE html>
<html lang="nl">
<head>
    <link rel="apple-touch-icon" sizes="180x180" href="/assetes/img/faicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/assetes/img/faicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/assetes/img/faicon/favicon-16x16.png">
    <link rel="manifest" href="/assetes/img/faicon/site.webmanifest">
    <link rel="mask-icon" href="/assetes/img/faicon/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PcBuilder Site</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="/assetes/js/flash.min.js"></script>
    <script src="https://kit.fontawesome.com/2baef102dd.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script><!--    <link rel="stylesheet" type="text/css" href="/assetes/css/base.css">-->
<!--    <link rel="stylesheet" type="text/css" href="/assetes/css/header.css">-->
    <link rel="stylesheet" type="text/css" href="/assetes/css/flash.min.css">

</head>
<style>
    .btn{
        color: white !important;
    }

    .link-dark{
        color: white !important;
    }
</style>
<body>
<main>
<div class="flex-shrink-0 p-3 bg-dark text-white" style="width: 280px;height: 100vh">
    <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
        <svg class="bi me-2" width="40" height="32"><use xlink:href="#bootstrap"></use></svg>
        <span class="fs-4">User panel</span>
    </a>
    <hr>
    <ul class="list-unstyled ps-0" style="color: white !important;">
        <li class="mb-1">
            <button class="btn btn-toggle align-items-center rounded" data-bs-toggle="collapse" data-bs-target="#home-collapse" aria-expanded="true">
                Home
            </button>
            <div class="collapse" id="home-collapse" style="">
                <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                    <li><a href="/account/user/orders" class="link-dark rounded">Orders</a></li>
                    <li><a href="/account/user/contact" class="link-dark rounded">Contact</a></li>
                </ul>
            </div>
        </li>
        <li class="mb-1">
            <button class="btn btn-toggle align-items-center rounded collapsed" data-bs-toggle="collapse" data-bs-target="#dashboard-collapse" aria-expanded="false">
                Dashboard
            </button>
            <div class="collapse" id="dashboard-collapse">
                <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                    <li><a href="/account/admin/orders" class="link-dark rounded">Orders</a></li>
                    <li><a href="/account/admin/reparaties" class="link-dark rounded">Reparaties</a></li>
                    <li><a href="/account/admin/vooraad" class="link-dark rounded">Vooraad</a></li>
                </ul>
            </div>
        </li>
        <li class="mb-1">
            <button class="btn btn-toggle align-items-center rounded collapsed" data-bs-toggle="collapse" data-bs-target="#orders-collapse" aria-expanded="false">
                Admin
            </button>
            <div class="collapse" id="orders-collapse">
                <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                    <li><a href="/account/admin/configs" class="link-dark rounded">Configs</a></li>
                    <li><a href="/account/admin/products" class="link-dark rounded">Producten</a></li>
                    <li><a href="/account/admin/stats" class="link-dark rounded">Statistics</a></li>
<!--                    <li><a href="#" class="link-dark rounded"></a></li>-->
                </ul>
            </div>
        </li>
    </ul>
    <hr>
    <div class="dropdown">
        <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
            <img src="https://github.com/mdo.png" alt="" width="32" height="32" class="rounded-circle me-2">
            <strong>mdo</strong>
        </a>
        <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser1" style="">
<!--            <li><a class="dropdown-item" href="#">New project...</a></li>-->
            <li><a class="dropdown-item" href="/account/user/settings">Settings</a></li>
            <li><a class="dropdown-item" href="/account/user/profile">Profile</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="/logout">Sign out</a></li>
        </ul>
    </div>

</div>