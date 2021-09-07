<?php
require __DIR__ . '/vendor/autoload.php';

use DevCoder\Route;
use Dotenv\Dotenv;
use PcBuilder\Framework\Execptions\TemplateNotFound;
use PcBuilder\Framework\Registery\PcBuilderRouter;
use PcBuilder\Modules\Controllers\IndexController;


$dotenv  = Dotenv::createImmutable(__DIR__);
$dotenv->load();


$router = new PcBuilderRouter([
    new Route('home_page', '/', [IndexController::class])
]);

foreach (range(1,10) as $i){
    $router->add(new Route('prebuild_'.$i ,'/prebuild/'.$i, [IndexController::class]));
}


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

} catch (Exception){

}