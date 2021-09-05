<?php

namespace PcBuilder\Framework\Registery;

class Controller
{

    private Template $templates;

    public function __construct()
    {
        $this->templates = new Template($_SERVER['DOCUMENT_ROOT'] .'\pages', []);
    }


    public function render($view,$context = [])
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