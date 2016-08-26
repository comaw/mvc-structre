<?php
/**
 * powered by php-shaman
 * Controller.php 26.08.2016
 * beejee
 */

namespace system;

use system\helper\ClassHelper;


/**
 * Class Controller
 * @package system
 */
class Controller
{
    public $view;

    public function __construct() {
        $name = ClassHelper::getClassName($this);
        $this->view = new View($name);
    }

    public function refresh(){
        header("Refresh:0");
    }
}