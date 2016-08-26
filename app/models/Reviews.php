<?php
/**
 * powered by php-shaman
 * Reviews.php 26.08.2016
 * beejee
 */

namespace app\models;


use system\Model;

/**
 * This is the model class for table "reviews".
 *
 * @property string $id
 * @property string $name
 * @property string $email
 * @property string $text
 * @property string $status
 * @property string $created
 *
 */
class Reviews extends Model
{

    public $id;
    public $name;
    public $email;
    public $text;
    public $status;
    public $created;

    /**
     * @return string
     */
    public function tableName(){
        return "reviews";
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Id',
            'name' => 'Имя',
            'email' => 'email',
            'text' => 'Текст сообщения',
            'status' => 'Статус',
            'created' => 'Создано',
        ];
    }

    /**
     * @return bool
     */
    public function validate(){
        if(!$this->data){
            $this->addError('Нет данных', '');
        }
        if(!trim($this->data['name'])){
            $this->addError('Нужно заполнить '.$this->getLabels('name'), 'name');
        }
        if(!filter_var($this->data['email'], FILTER_VALIDATE_EMAIL)){
            $this->addError('Неверный '.$this->getLabels('email'), 'email');
        }
        if(!trim($this->data['text'])){
            $this->addError('Нужно заполнить поле '.$this->getLabels('text'), 'text');
        }
        parent::validate();
        return true;
    }

    public function insert(){
        $this->db->insert($this->tableName(), $this->data);
    }
}