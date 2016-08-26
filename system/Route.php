<?php
/**
 * powered by php-shaman
 * Route.php 26.08.2016
 * beejee
 */

namespace system;

/**
 * Class Route
 * @package system
 */
class Route
{
    const DEFAULT_CONTROLLER = 'home';
    const DEFAULT_METHOD = 'index';

    protected $controller = '';
    protected $method = '';
    protected $argm = [];

    /**
     * Route constructor.
     */
    public function __construct() {
        $this->setCurrentRoute();
    }

    /**
     * set controller anf method
     */
    protected function setCurrentRoute(){
        $requestUrl = Request::getParam('requestUri');
        $requestUrl = explode('?', $requestUrl);
        $requestUrl = isset($requestUrl[0]) ? ltrim($requestUrl[0], '/') : null;
        $requestUrl = explode('/', $requestUrl);
        $this->controller = self::DEFAULT_CONTROLLER;
        $this->method = self::DEFAULT_METHOD;
        if(isset($requestUrl[0]) && $requestUrl[0]){
            $this->controller = $requestUrl[0];
            if(isset($requestUrl[1]) && $requestUrl[1]){
                $this->method = $requestUrl[1];
            }
        }
        for ($i = 2; $i < sizeof($requestUrl); $i++){
            if(isset($requestUrl[$i]) && $requestUrl[$i]){
                $this->argm[] = $requestUrl[$i];
            }
        }
        $this->controller = ucfirst($this->controller);
        $this->method = ucfirst($this->method);
    }

    /**
     * @return array
     */
    public function getCurrentRoute(){
        return [
            'controller' => $this->controller,
            'method' => $this->method,
            'argm' => $this->argm,
        ];
    }
}