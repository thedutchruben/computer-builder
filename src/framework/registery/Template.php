<?php

namespace PcBuilder\Framework\Registery;

class Template
{

    private $path;

    private $parameters = [];

    public function __construct(string $path, array $parameters = [])
    {
        $this->path = rtrim($path, '/').'\\';
        $this->parameters = $parameters;
    }

    public function render(string $view, array $context = [])
    {
        if (!file_exists($file = $this->path.$view)) {
            var_dump("File not found");
            throw new \Exception(sprintf('The file %s could not be found.', $view));
        }

        extract(array_merge($context, ['template' => $this]));

        include ($file);
    }

    public function get(string $key)
    {
        return $this->parameters[$key] ?? null;
    }
}