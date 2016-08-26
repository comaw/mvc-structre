<?php
/**
 * powered by php-shaman
 * index.php 26.08.2016
 * beejee
 */

error_reporting (E_ALL);

include_once(__DIR__.'/system/Autoload.php');

$config = require_once(__DIR__.'/app/config/config.php');
$core = new \system\Core($config);

