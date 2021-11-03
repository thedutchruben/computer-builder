<?php
require __DIR__ . '/vendor/autoload.php';

use DevCoder\Route;
use Dotenv\Dotenv;
use PcBuilder\Framework\Execptions\TemplateNotFound;
use PcBuilder\Framework\Registery\PcBuilderRouter;
use PcBuilder\Framework\Registery\Template;
use PcBuilder\Modules\Controllers\AdminController;
use PcBuilder\Modules\Controllers\ConfiguratorController;
use PcBuilder\Modules\Controllers\CustomerController;
use PcBuilder\Modules\Controllers\IndexController;
use PcBuilder\Modules\Controllers\UserController;


$dotenv  = Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

session_start();

$router = new PcBuilderRouter([
    //Default routing
    new Route('home_page', '/', [IndexController::class,"index"]),
    new Route('siteMap', '/sitemap', [IndexController::class,"siteMap"]),
    new Route('contact', '/contact', [IndexController::class,"contact"]),

    //Config Routing
    new Route('custom_pc', '/custom-pc/{id}', [ConfiguratorController::class,"Configurator"],['GET']),
    new Route('add_pc', '/custom-pc/{id}', [ConfiguratorController::class,"Configurator_Post"],['POST']),

    //card routing
    new Route('card', '/card', [IndexController::class,"card"]),


    //Account Routing
    new Route('user_login', '/login', [UserController::class,"login"], ['POST','GET']),
    new Route('user_register', '/register', [UserController::class,"register"], ['POST','GET']),

    //Customer Routing
    new Route('customer_index', '/customer', [CustomerController::class,"index"]),
    new Route('customer_orders', '/customer/orders', [CustomerController::class,"order"]),
    new Route('customer_order', '/customer/orders/{id}', [CustomerController::class,"orderInfo"]),

    //admin
    new Route('admin_index', '/admin', [AdminController::class,"index"]),
    new Route('admin_orders', '/admin/orders', [AdminController::class,"orders"]),
    new Route('admin_configs', '/admin/configs', [AdminController::class,"configs"]),
    new Route('admin_config', '/admin/configs/{id}', [AdminController::class,"config"]),
]);



try {

    $route = $router->matchFromPath($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);

    $parameters = $route->getParameters();
    $arguments = $route->getVars();

    $controllerName = $parameters[0];
    $methodName = $parameters[1] ?? null;

    $controller = new $controllerName();
    if (!is_callable($controller)) {
        $controller =  [$controller, $methodName];
    }

    echo $controller(...array_values($arguments));

} catch (TemplateNotFound $exception) {
    $template = new Template($_SERVER['DOCUMENT_ROOT'] .'\pages', []);
    $template->render('ErrorPage.php',[
        "errorName" => "Tempate not found"
    ]);
} catch (Exception $exception){
    $template = new Template($_SERVER['DOCUMENT_ROOT'] .'\pages', []);
    echo $_ENV['APP_ENV'] ;
    if($_ENV['APP_ENV'] == "dev"){
        var_dump($exception);
    }
    $template->render('ErrorPage.php',[
        "errorName" => $exception->getMessage()
    ]);

}