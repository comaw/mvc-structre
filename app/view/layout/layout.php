<?php
/**
 * powered by php-shaman
 * layout.php 26.08.2016
 * beejee
 */

/* @var $content string */

use system\View;

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="php-shaman">
    <title>Форма обратной связи</title>
    <link href="/css/jquery-ui.min.css" rel="stylesheet">
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/bootstrap-theme.min.css" rel="stylesheet">
    <link href="/css/style.css" rel="stylesheet">
    <link rel="shortcut icon" href="/css/favicon.ico">
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <h1>Форма обратной связи</h1>
        </div>
    </div>
    <?=$content?>
</div>
<script src="/js/jquery.min.js"></script>
<script src="/js/jquery-ui.min.js"></script>
<script src="/js/bootstrap.min.js"></script>
<script src="/js/home.js"></script>
</body>
</html>
