<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
// change the following paths if necessary
$yii=dirname(__FILE__).'/../framework/yii.php';
$config=dirname(__FILE__).'/protected/config/main.php';
defined('YII_DEBUG') or define('YII_DEBUG',true);
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);

require_once($yii);

Yii::createWebApplication($config)->run();

$admin_id = 2;
$i = 9;

//    $model = new Partner();
//    $model->fio  = 'Новый юзер 2_'.$i;
//    $model->email = 'email2'.$i.'@mail.ru';
//    $model->password = '165178b07b52b5f851cf6c9be7c9dc28';
//    $model->balance = 4000;
//
//    $admin = Partner::model()->findByPk($admin_id);
//    $model->appendTo($admin);

//    $model = Partner::model()->findByPk(11);
//    $model->deleteNode();
