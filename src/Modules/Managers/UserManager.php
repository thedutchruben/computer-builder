<?php

namespace PcBuilder\Modules\Managers;

use PcBuilder\Framework\Registery\Manager;

class UserManager extends Manager
{

    public function register($vars = []){

    }

    public function login($vars = []){

    }


    public function is_authenticated() : bool
    {

        return true;
    }

}