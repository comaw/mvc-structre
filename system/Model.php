<?php
/**
 * powered by php-shaman
 * Model.php 26.08.2016
 * beejee
 */

namespace system;

use system\database\PDOMySQL;
use system\helper\ClassHelper;


/**
 * Class Model
 * @package system
 */
class Model
{
    /**
     * @var \PDOMySQL
     */
    protected $db;
    public $data = [];
    public $errors = [];

    /**
     * Model constructor.
     */
    public function __construct() {
        $dbConfig = Config::getConfig('db');
        $this->db = new PDOMySQL($dbConfig['dsn'], $dbConfig['user'], $dbConfig['pass']);
    }

    /**
     * @return string
     */
    public function className(){
        return ClassHelper::getClassName($this);
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [];
    }

    /**
     * @param array $post
     *
     * @return bool
     */
    public function load($post){
        if(!isset($post[$this->className()])){
            return false;
        }
        $this->data = $post[$this->className()];
        return true;
    }

    /**
     * @param null|string $name
     *
     * @return array|mixed|null
     */
    public function getLabels($name = null){
        if($name){
            return isset($this->attributeLabels()[$name]) ? $this->attributeLabels()[$name] : $name;
        }
        return $this->attributeLabels();
    }

    /**
     *  Validate post data
     */
    public function validate(){
        foreach ($this->data AS $k => $v){
            if(!property_exists($this, $k)){
                unset($this->data[$k]);
            }
        }
    }

    /**
     * @param string $message
     * @param string $field
     */
    public function addError($message, $field = ''){
        if(!$field){
            $this->errors[] = $message;
        }else{
            $this->errors[$field] = $message;
        }
    }

    /**
     * @return bool
     */
    public function hasError(){
        return sizeof($this->errors) > 0 ? true : false;
    }

    /**
     * @param string $field
     *
     * @return bool
     */
    public function hasErrorField($field){
        return isset($this->errors[$field]) && $this->errors[$field] ? true : false;
    }

    /**
     * @param string $field
     *
     * @return mixed|null
     */
    public function getError($field){
        return isset($this->errors[$field]) ? $this->errors[$field] : null;
    }

    /**
     * @param bool $inString
     *
     * @return array|string
     */
    public function getErrors($inString = false){
        if($inString){
            return '<p>'.join('</p><p>', $this->errors).'</p>';
        }
        return $this->errors;
    }
}