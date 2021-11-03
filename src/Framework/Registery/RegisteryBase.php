<?php

namespace PcBuilder\Framework\Registery;

use PcBuilder\Framework\Database\Mysql;

/**
 * The base of the registery
 * In this class the flasher and mysql has been implemented
 */
class RegisteryBase
{

    private Mysql $mysql;
    public function __construct()
    {
        $this->mysql = new Mysql();

    }

    /**
     * @return Mysql
     */
    public function getMysql(): Mysql
    {
        return $this->mysql;
    }


    public function flasher_success($message,$settings = []){
        if(isset($settings)){
            if(isset($settings['oneTimeSession'])){
                if(isset($_SESSION[$message])){
                    return;
                }else{
                    $_SESSION[$message] = true;
                }
            }
        }
        echo "<script>";
        echo "window.FlashMessage.success('".$message."');";
        echo "</script>";
    }

    public function flasher_error($message,$settings = []){
        if(isset($settings)){
            if(isset($settings['oneTimeSession'])){
                if(isset($_SESSION[$message])){
                    return;
                }else{
                    $_SESSION[$message] = true;
                }
            }
        }
        echo "<script>";
        echo "window.FlashMessage.error('".$message."');";
        echo "</script>";
    }

    public function flasher_warning($message,$settings = []){
        if(isset($settings)){
            if(isset($settings['oneTimeSession'])){
                if(isset($_SESSION[$message])){
                    return;
                }else{
                    $_SESSION[$message] = true;
                }
            }
        }
        echo "<script>";
        echo "window.FlashMessage.warning('".$message."');";
        echo "</script>";
    }
}