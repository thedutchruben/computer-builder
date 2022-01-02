<?php

namespace PcBuilder\Framework\Registry;

use PcBuilder\Framework\Execptions\TemplateNotFound;

/**
 * The template will render variables in to a php file
 */
class Template
{

    /**
     * @var string The path where
     */
    private string $path;

    /**
     * @var array The default values for al the pages
     */
    private array $parameters = [];

    public function __construct(string $path, array $parameters = [])
    {
        $this->path = rtrim($path, '/').'\\';
        $this->parameters = $parameters;
    }

    /**
     * @param string $view The name of the view file
     * @param array $context The data to render on the page
     * @throws TemplateNotFound If the template is not found
     */
    public function render(string $view, array $context = [])
    {
        if (!file_exists($file = $this->path.$view)) {
            throw new TemplateNotFound(sprintf('The file %s could not be found.', $view));
        }

        extract(array_merge($context, ['template' => $this]));

        include ($file);
    }

    public function get(string $key)
    {
        return $this->parameters[$key] ?? null;
    }
}