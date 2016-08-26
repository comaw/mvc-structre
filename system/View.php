<?php
/**
 * powered by php-shaman
 * View.php 26.08.2016
 * beejee
 */

namespace system;

/**
 * Class View
 * @package system
 */
class View
{
    public $viewDir = 'app/view/';
    public $ext = '.php';
    public $controllerDir = '';

    /**
     * View constructor.
     *
     * @param string $controllerName
     */
    public function __construct($controllerName = '') {
        $this->viewDir = __DIR__. '/../' . $this->viewDir;
        $controllerName = str_replace('controller', '', strtolower($controllerName));
        $this->controllerDir = $controllerName . '/';
    }

    public static function partial($template, $params = []){
        $view = new self();
        return $view->fetchPartial($template, $params);
    }

    /**
     * @param $template
     * @param array $params
     *
     * @return string
     * @throws \Exception
     */
    public function fetchPartial($template, $params = []){
        $fileName = $this->viewDir . $this->controllerDir . $template . $this->ext;
        if(!file_exists($fileName)){
            throw new \Exception('Not file found ' . $fileName, 501);
        }
        extract($params);
        ob_start();
        include $fileName;
        return ob_get_clean();
    }

    /**
     * @param string $template
     * @param array $params
     * @param bool $return
     *
     * @return string|null
     */
    public function renderPartial($template, $params = [], $return = true){
        if($return){
            return $this->fetchPartial($template, $params);
        }
        echo $this->fetchPartial($template, $params);
        return null;
    }

    /**
     * @param string $template
     * @param array $params
     *
     * @return string
     */
    public function fetch($template, $params = []){
        $params['content'] = $this->fetchPartial($template, $params);
        return $this->fetchPartial('/../layout/layout', $params);
    }

    /**
     * @param $template
     * @param array $params
     */
    public function render($template, $params = []){
        echo $this->fetch($template, $params);
    }
}