<?php
require __DIR__ . '/vendor/autoload.php';

use DevCoder\Route;
use Dotenv\Dotenv;
use PcBuilder\Framework\Execptions\TemplateNotFound;
use PcBuilder\Framework\Registery\PcBuilderRouter;
use PcBuilder\Framework\Registery\Template;
use PcBuilder\Modules\Controllers\IndexController;


$dotenv  = Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();


$router = new PcBuilderRouter([
    new Route('home_page', '/', [IndexController::class]),
    new Route('siteMap', '/sitemap', [IndexController::class,"siteMap"]),
    new Route('siteMap', '/contact', [IndexController::class,"contact"])

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
    $template->render('ErrorPage.php',[
        "errorName" => $exception->getMessage()
    ]);
}