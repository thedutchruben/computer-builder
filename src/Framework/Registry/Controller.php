<?php

namespace PcBuilder\Framework\Registry;

use PcBuilder\Framework\Execptions\TemplateNotFound;

class Controller
extends RegistryBase{

    private Template $templates;

    public function __construct()
    {
        parent::__construct();
        $this->templates = new Template($_SERVER['DOCUMENT_ROOT'] .'\pages', []);
    }


    /**
     * @throws TemplateNotFound
     */
    public function render($view, $context = [])
    {
        $this->getTemplates()->render($view, $context);
    }


    /**
     * @return mixed
     */
    public function getTemplates() : Template
    {
        return $this->templates;
    }

}