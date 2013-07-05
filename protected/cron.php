<?php
/**
 * Created by JetBrains PhpStorm.
 * User: artem
 * Date: 12.06.13
 * Time: 16:27
 * To change this template use File | Settings | File Templates.
 */
$yii=dirname(__FILE__).'/../../framework/yii.php';
$config=dirname(__FILE__).'/config/cron.php';

require_once($yii);

// remove the following line when in production mode
defined('YII_DEBUG') or define('YII_DEBUG',true);

Yii::createConsoleApplication($config)->run();

