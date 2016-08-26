<?php
/**
 * powered by php-shaman
 * HomeController.php 26.08.2016
 * beejee
 */

namespace app\controller;


use app\models\Reviews;
use system\Controller;
use system\Request;


class HomeController extends Controller
{
    public function actionIndex(){
        $model = new Reviews();
        if($model->load(Request::postData())){
            if($model->validate()){
                $model->insert();
                $this->refresh();
            }
        }
        $this->view->render('index', [
            'model' => $model
        ]);
    }
}