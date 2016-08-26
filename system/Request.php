<?php
/**
 * powered by php-shaman
 * Request.php 26.08.2016
 * beejee
 */

namespace system;

/**
 * Class Request
 * @package system
 */
class Request
{

    /**
     * @var array
     */
    private static $params = [];

    /**
     * @var array
     */
    private static $listParamsName = [
        'httpAccept' => 'HTTP_ACCEPT',
        'httpAcceptLanguage' => 'HTTP_ACCEPT_LANGUAGE',
        'httpHost' => 'HTTP_HOST',
        'referrer' => 'HTTP_REFERER',
        'userAgent' => 'HTTP_USER_AGENT',
        'iserIp' => 'REMOTE_ADDR',
        'serverName' => 'SERVER_NAME',
        'serverPort' => 'SERVER_PORT',
        'serverSoftware' => 'SERVER_SOFTWARE',
        'serverProtocol' => 'SERVER_PROTOCOL',
        'requestMethod' => 'REQUEST_METHOD',
        'queryString' => 'QUERY_STRING',
        'requestUri' => 'REQUEST_URI'
    ];

    /**
     * set params for request
     */
    public static function setParams(){
        foreach (self::$listParamsName AS $name => $value){
            self::$params[$name] = self::getParamsInServer($value);
        }
    }

    /**
     * @param string $name
     *
     * @return mixed|null
     */
    public static function getParam($name){
        return isset(static::$params[$name]) ? static::$params[$name] : null;
    }

    /**
     * @param string $name
     *
     * @return null
     */
    private static function getParamsInServer($name){
        return isset($_SERVER[$name]) ? $_SERVER[$name] : null;
    }

    /**
     * @param null|string $name
     *
     * @return null|string|array
     */
    public static function getData($name = null){
        if($name){
            return isset($_GET[$name]) ? $_GET[$name] : null;
        }
        return sizeof($_GET) > 0 ? $_GET : [];;
    }

    /**
     * @param null|string $name
     * @param null|string $subName
     *
     * @return array|null
     */
    public static function postData($name = null, $subName = null){
        if($name){
            if($subName){
                return isset($_POST[$name][$subName]) ? $_POST[$name][$subName] : null;
            }
            return isset($_POST[$name]) ? $_POST[$name] : null;
        }
        return sizeof($_POST) > 0 ? $_POST : [];
    }
}