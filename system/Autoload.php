<?php
/**
 * powered by php-shaman
 * Autoload.php 26.08.2016
 * beejee
 */

namespace system;

/**
 * Class Autoload
 * @package system
 */
class Autoload
{

    /**
     * Autoload constructor.
     */
    public function __construct(){}

    /**
     * @param $file
     *
     * @throws \Exception
     */
    public static function autoload($file){
        $file = str_replace('\\', '/', $file);
        $path = __DIR__.'/../';
        $filePath = $path . $file . '.php';
        if(!file_exists($filePath)){
            throw new \Exception('Not file found '.$file, 500);
        }
        require_once($filePath);
    }
}

\spl_autoload_register('system\Autoload::autoload');