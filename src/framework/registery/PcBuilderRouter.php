<?php

namespace PcBuilder\Framework\Registery;

use DevCoder\Exception\RouteNotFound;
use DevCoder\Route;
use DevCoder\UrlGenerator;
use Psr\Http\Message\ServerRequestInterface;

class PcBuilderRouter implements \DevCoder\RouterInterface
{

    private const NO_ROUTE = 404;

    /**
     * @var \ArrayObject<Route>
     */
    private $routes;

    /**
     * @var UrlGenerator
     */
    private $urlGenerator;

    /**
     * Router constructor.
     * @param $routes array<Route>
     */
    public function __construct(array $routes = [])
    {
        $this->routes = new \ArrayObject();
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

    public function matchFromPath(string $path, string $method): Route
    {
        foreach ($this->routes as $route) {
            if ($route->match($path, $method) === false) {
                continue;
            }

            return $route;
        }

        throw new RouteNotFound(
            'No route found for ' . $method,
            self::NO_ROUTE
        );
    }

    public function generateUri(string $name, array $parameters = []): string
    {
        return $this->urlGenerator->generate($name, $parameters);
    }

    public function getUrlGenerator(): UrlGenerator
    {
        return $this->urlGenerator;
    }
}