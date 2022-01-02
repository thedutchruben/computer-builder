<?php

namespace PcBuilder\Framework\Registry;

use PcBuilder\Framework\Cache\CacheObject;
use PcBuilder\Framework\Database\Mysql;
use PcBuilder\Objects\Message;

/**
 * The base of the registry
 * In this class the flasher and mysql has been implemented
 */
class RegistryBase
{

    /**
     * The mysql connection for the website
     * @var Mysql
     */
    private Mysql $mysql;

    /**
     * Setup all the needed data for the registry
     */
    public function __construct()
    {
        $this->mysql = new Mysql();

    }

    /**
     * Get the mysql connection
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
     * Get the cache file and put the data if needed
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

    /**
     * Render a success flash
     * @param string $message
     * @param array $settings
     * @return void
     */
    public function flasher_success(string $message, array $settings = []){
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

    /**
     * Render a error flash
     * @param string $message
     * @param array $settings
     * @return void
     */
    public function flasher_error(string $message, array $settings = []){
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

    /**
     * Render a warning flash
     * @param string $message
     * @param array $settings
     * @return void
     */
    public function flasher_warning(string $message,array $settings = []){
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


    /**
     * Get the users ip
     * For logging only!
     * @return mixed
     */
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