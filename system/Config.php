<?php
/**
 * powered by php-shaman
 * Config.php 26.08.2016
 * beejee
 */

namespace system;

/**
 * Class Config
 * @package system
 */
class Config
{
    /**
     * @var array
     */
    private static $config = [];

    /**
     * @param array $config
     *
     * @return bool
     */
    public static function setConfig(array $config) {
        if(sizeof($config) < 1){
            return false;
        }
        foreach ($config AS $name => $value){
            self::$config[$name] = $value;
        }
        return true;
    }

    /**
     * @param $name
     *
     * @return mixed|null
     */
    public static function getConfig($name){
        return isset(static::$config[$name]) ? static::$config[$name] : null;
    }

    /**
     * @param $name
     * @param $paramsName
     *
     * @return mixed|null
     */
    public static function getConfigParams($name, $paramsName){
        return isset(static::$config[$name]) && isset(static::$config[$name][$paramsName]) ? static::$config[$name][$paramsName] : null;
    }
}