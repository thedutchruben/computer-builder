<?php

namespace PcBuilder\Framework\Registry;

use PcBuilder\Framework\Exception\TemplateNotFound;

/**
 * The base of the controllers
 */
class Controller extends RegistryBase{

    /**
     * All the templates
     * @var Template
     */
    private Template $templates;

    /**
     * Setup all the links that are needed
     */
    public function __construct()
    {
        parent::__construct();
        $this->templates = new Template($_SERVER['DOCUMENT_ROOT'] .'\pages', []);
    }


    /**
     * Render a page with the needed items
     * @throws TemplateNotFound
     */
    public function render($view, $context = [])
    {
        $this->getTemplates()->render($view, $context);
    }


    /**
     * Get all the templates
     * @return mixed
     */
    public function getTemplates() : Template
    {
        return $this->templates;
    }

}