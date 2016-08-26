<?php
/**
 * powered by php-shaman
 * index.php 26.08.2016
 * beejee
 */

/* @var $model \app\models\Reviews */

use system\View;
use system\Request;

?>

<div class="row">
    <div class="col-xs-12">
    <?=View::partial('home/review')?>
    </div>
</div>
<div class="row">
    <div class="col-xs-12">
        <?php if($model->hasError()){ ?>
        <div class="row">
            <div class="col-xs-12 text-danger text-center">
            <?=$model->getErrors(true)?>
            </div>
        </div>
        <?php } ?>
        <form class="form-horizontal" id="form-reviews" action="/" method="post" enctype="multipart/form-data" accept-charset="UTF-8">
            <div class="form-group">
                <label for="name" class="col-sm-2 control-label">Имя</label>
                <div class="col-sm-10">
                    <input type="text" name="<?=$model->className()?>[name]" value="<?=Request::postData($model->className(), 'name')?>" class="form-control" id="name" placeholder="Имя">
                </div>
            </div>
            <div class="form-group">
                <label for="email" class="col-sm-2 control-label">Email</label>
                <div class="col-sm-10">
                    <input type="email" name="<?=$model->className()?>[email]" value="<?=Request::postData($model->className(), 'email')?>" class="form-control" id="email" placeholder="Email">
                </div>
            </div>
            <div class="form-group">
                <label for="text" class="col-sm-2 control-label">Текст сообщения</label>
                <div class="col-sm-10">
                    <textarea id="text" name="<?=$model->className()?>[text]" placeholder="Текст сообщения" rows="6" class="form-control"><?=Request::postData($model->className(), 'text')?></textarea>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" class="btn btn-success">Добавить</button>
                    <button type="button" class="btn btn-info">Предварительный просмотр</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script language="JavaScript">
    var FormError = {
        'name' : <?=(int)$model->hasErrorField('name')?>,
        'email' : <?=(int)$model->hasErrorField('email')?>,
        'text' : <?=(int)$model->hasErrorField('text')?>
    };
</script>