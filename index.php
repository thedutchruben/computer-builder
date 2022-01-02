<?php
require __DIR__ . '/vendor/autoload.php';

use DevCoder\Route;
use Dotenv\Dotenv;
use PcBuilder\Framework\Execptions\TemplateNotFound;
use PcBuilder\Framework\Registry\PcBuilderRouter;
use PcBuilder\Framework\Registry\Template;
use PcBuilder\Modules\Controllers\AdminController;
use PcBuilder\Modules\Controllers\ConfiguratorController;
use PcBuilder\Modules\Controllers\CustomerController;
use PcBuilder\Modules\Controllers\IndexController;
use PcBuilder\Modules\Controllers\UserController;


//Load the .env file
$dotenv  = Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

//Start the session
session_start();

/**
 * Register the routing
 */
$router = new PcBuilderRouter([
    //Default routing
    new Route('home_page', '/', [IndexController::class,"index"]),
    new Route('siteMap', '/sitemap', [IndexController::class,"siteMap"]),
    new Route('contact', '/contact', [IndexController::class,"contact"]),

    //Config Routing
    new Route('custom_pc', '/custom-pc/{id}', [ConfiguratorController::class,"Configurator"],['GET']),
    new Route('add_pc', '/custom-pc/{id}', [ConfiguratorController::class,"Configurator_Post"],['POST']),

    //cart routing
    new Route('cart', '/cart', [IndexController::class,"cart"]),
    new Route('checkout', '/checkout', [IndexController::class,"checkout"],['GET']),
    new Route('checkout_pay', '/checkout', [IndexController::class,"checkout_post"],['POST']),


    //Account Routing
    new Route('user_login', '/login', [UserController::class,"login"], ['POST','GET']),
    new Route('user_register', '/register', [UserController::class,"register"], ['POST','GET']),

    //Customer Routing
    new Route('customer_index', '/customer', [CustomerController::class,"index"]),
    new Route('customer_orders', '/customer/orders', [CustomerController::class,"orders"]),
    new Route('customer_order', '/customer/order/{id}', [CustomerController::class,"orderInfo"]),

    //admin
    new Route('admin_index', '/admin', [AdminController::class,"index"]),
    //Admin product
    new Route('admin_products', '/admin/products', [AdminController::class,"products"]),
    new Route('admin_products_create', '/admin/products/create', [AdminController::class,"registerProduct"],['POST','GET']),
    new Route('admin_products_edit', '/admin/products/{id}/edit', [AdminController::class,"editProduct"],['GET']),
    new Route('admin_products_delete', '/admin/products/{id}/delete', [AdminController::class,"deleteProduct"]),
    new Route('admin_products_update', '/admin/products/update', [AdminController::class,"updateProduct"],['POST']),

    //Admin orders
    new Route('admin_orders', '/admin/orders', [AdminController::class,"orders"]),
    new Route('admin_order', '/admin/order/{id}', [AdminController::class,"orderInfo"]),
    new Route('admin_order_update', '/admin/order/{id}/update', [AdminController::class,"updateOrder"],['POST']),

    //Admin configs
    new Route('admin_configs', '/admin/configs', [AdminController::class,"configs"]),
    new Route('admin_config', '/admin/config/{id}', [AdminController::class,"config"]),
    new Route('admin_config_create', '/admin/config/create', [AdminController::class,"createConfig"], ['POST','GET']),
    new Route('admin_config_save', '/admin/config/{id}/save', [AdminController::class,"saveConfig"], ['POST']),

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
    if($_ENV['APP_ENV'] == "dev"){
        var_dump($exception);
    }
    $template->render('ErrorPage.php',[
        "errorName" => $exception->getMessage()
    ]);

}


//Render flashers
if(isset($_SESSION['messages'])){
    foreach ($_SESSION['messages' ] as $message){
        if(isset($message['data']->getOptions()['oneTimeSession'])) return;
        if(isset($message['data']->getOptions()['showTill'])){
            if($message['data']->getOptions()['showTill'] <= microtime(true)){
                return;
            }
        }
        var_dump($message['data']);
        switch ($message['data']['type']){
            case "success":
                echo "<script>";
                echo "window.FlashMessage.success('".$message['data']->getText()."');";
                echo "</script>";
                break;
            case "warning":
                echo "<script>";
                echo "window.FlashMessage.warning('".$message['data']->getText()."');";
                echo "</script>";
                break;
            case "error":
                echo "<script>";
                echo "window.FlashMessage.error('".$message['data']->getText()."');";
                echo "</script>";
                break;
        }

    }
}

