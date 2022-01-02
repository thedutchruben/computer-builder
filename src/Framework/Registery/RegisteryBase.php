<?php

namespace PcBuilder\Framework\Registery;

use PcBuilder\Framework\Cache\CacheObject;
use PcBuilder\Framework\Database\Mysql;
use PcBuilder\Objects\Message;

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

    /**
     * Check if the cache exist and is valid
     * @param string $name
     * @return bool
     */
    private function isCache(string $name) : bool
    {
        if(file_exists("cache/".$name.".json")){
            if(json_decode(file_get_contents("cache/".$name.".json"),true)['endTime'] >= microtime(true)){
                return true;
            }else{
                unlink("cache/".$name.".json");
            }
        }
        return false;
    }

    /**
     * @param string $name
     * @param float $time
     * @param mixed $data
     * @return CacheObject
     */
    public function getCache(string $name,float $time,mixed $data) : CacheObject
    {
        $cache = new CacheObject();
        if($this->isCache($name)){

            $data = json_decode(file_get_contents("cache/".$name.".json"),true);
            $cache->setId($data['id']);
            $cache->setEndTime($data['endTime']);
            $cache->setData($data['data']);
        }else{
            $cache->setId($name);
            $cache->setEndTime(microtime(true) + $time);
            $cache->setData($data);
            $cache->save($cache);
        }

        return $cache;
    }

    public function render_flashers(){
        foreach ($_SESSION['messages'] as $message){
            if(isset($message['showTill'])){
                if(!$message['showTill'] <= microtime(true)){
                    return;
                }
            }
            echo "<script>";
            echo "window.FlashMessage.success('".$message."');";
            echo "</script>";
        }
    }

    public function flasher_success(string $message,$settings = []){
        $messageObject = new Message();
        $messageObject->setText($message);
        $messageObject->setOptions($settings);
        $messageObject->setText("success");
        $_SESSION['messages'][$message]['data'] = $messageObject;
        if(isset($settings)){
            if(isset($settings['oneTimeSession'])){
                if(isset($_SESSION['messages'][$message]['data']->getOptions()['oneTimeSession'])){
                    return;
                }else{
                    $_SESSION['messages'][$message]['data']->getOptions()['oneTimeSession'] = true;
                }
            }
        }
        echo "<script>";
        echo "window.FlashMessage.success('".$message."');";
        echo "</script>";
    }

    public function flasher_error($message,$settings = []){
        $messageObject = new Message();
        $messageObject->setText($message);
        $messageObject->setOptions($settings);
        $messageObject->setText("error");
        $_SESSION['messages'][$message]['data'] = $messageObject;
        if(isset($settings)){
            if(isset($settings['oneTimeSession'])){
                if(isset($_SESSION['messages'][$message]['data']->getOptions()['oneTimeSession'])){
                    return;
                }else{
                    $_SESSION['messages'][$message]['data']->getOptions()['oneTimeSession'] = true;
                }
            }
        }
        echo "<script>";
        echo "window.FlashMessage.error('".$message."');";
        echo "</script>";
    }

    public function flasher_warning($message,$settings = []){
        $messageObject = new Message();
        $messageObject->setText($message);
        $messageObject->setOptions($settings);
        $messageObject->setText("warning");
        $_SESSION['messages'][$message]['data'] = $messageObject;
        if(isset($settings)){
            if(isset($settings['oneTimeSession'])){
                if(isset($_SESSION['messages'][$message]['data']->getOptions()['oneTimeSession'])){
                    return;
                }else{
                    $_SESSION['messages'][$message]['data']->getOptions()['oneTimeSession'] = true;
                }
            }
        }
        echo "<script>";
        echo "window.FlashMessage.warning('".$message."');";
        echo "</script>";
    }


    public function getIp(){
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }
}