<?php
/**
 * powered by php-shaman
 * ClassHelper.php 26.08.2016
 * beejee
 */

namespace system\helper;

/**
 * Class ClassHelper
 * @package system\helper
 */
class ClassHelper
{
    /**
     * @param $class
     *
     * @return string
     */
    public static function getClassName($class){
        return join('', array_slice(explode('\\', get_class($class)), -1));
    }
}