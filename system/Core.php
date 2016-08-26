<?php
/**
 * powered by php-shaman
 * Core.php 26.08.2016
 * beejee
 */

namespace system;

/**
 * Class Core
 * @package system
 */
class Core
{
    /**
     * Core constructor.
     *
     * @param array $config
     */
    public function __construct(array $config) {
        Config::setConfig($config);
        Request::setParams();
        $this->activeController();
    }

    protected function activeController(){
        $route = new Route();
        $currentController = $route->getCurrentRoute();
        $controllerName = '\\app\\controller\\' . $currentController['controller'].'Controller';
        $controller = new $controllerName;
        $actionNameName = 'action' . $currentController['method'];
        if(!method_exists($controller, $actionNameName)){
            throw new \Exception('Not fount action '.$currentController['method'], 404);
        }
        $controller->$actionNameName($currentController['argm']);
    }
}