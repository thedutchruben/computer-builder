<?php

namespace PcBuilder\Framework\Registery;

use ArrayObject;
use DevCoder\Exception\RouteNotFound;
use DevCoder\Route;
use DevCoder\UrlGenerator;
use PcBuilder\Framework\Execptions\TemplateNotFound;
use Psr\Http\Message\ServerRequestInterface;

class PcBuilderRouter extends RegisteryBase implements \DevCoder\RouterInterface
{

    private const NO_ROUTE = 404;

    /**
     * @var ArrayObject<Route>
     */
    private ArrayObject $routes;

    /**
     * @var UrlGenerator
     */
    private UrlGenerator $urlGenerator;

    /**
     * Router constructor.
     * @param $routes array<Route>
     */
    public function __construct(array $routes = [])
    {
        parent::__construct();
        $this->routes = new ArrayObject();
        $this->urlGenerator = new UrlGenerator($this->routes);
        foreach ($routes as $route) {
            $this->add($route);
        }
    }

    public function add(Route $route): self
    {
        $this->routes->offsetSet($route->getName(), $route);
        return $this;
    }

    public function match(ServerRequestInterface $serverRequest): Route
    {
        return $this->matchFromPath($serverRequest->getUri()->getPath(), $serverRequest->getMethod());
    }

    /**
     * @throws TemplateNotFound
     */
    public function matchFromPath(string $path, string $method): Route
    {
        foreach ($this->routes as $route) {
            if ($route->match($path, $method) === false) {
                continue;
            }
            try{
                $name = $route->getName();
                $path = $route->getPath();
                $params =json_encode($route->getParameters());
                $methods = json_encode($route->getMethods());
                $vars = json_encode($route->getVarsNames());
                $this->getMysql()->getPdo()->exec("INSERT INTO `pc-builder`.`request_log`
(`name`,
`path`,
`parameters`,
`methods`,
`vars`)
VALUES(
'$name',
'$path',
'$params',
'$methods',
'$vars');");
            }catch (\Exception $exception){
                $this->flasher_error("Er is iets fout gegaan!");
            }

            return $route;
        }

        //Throw Exception if the page is not found
        throw new TemplateNotFound(
            "Deze pagina is niet gevonden!",
            self::NO_ROUTE
        );
    }

    /**
     * @param string $name
     * @param array $parameters
     * @return string the generated url
     */
    public function generateUri(string $name, array $parameters = []): string
    {
        return $this->urlGenerator->generate($name, $parameters);
    }

    /**
     * Returns the url generator
     * @return UrlGenerator
     */
    public function getUrlGenerator(): UrlGenerator
    {
        return $this->urlGenerator;
    }
}