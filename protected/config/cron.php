<?php
/**
 * Created by JetBrains PhpStorm.
 * User: artem
 * Date: 12.06.13
 * Time: 16:28
 * To change this template use File | Settings | File Templates.
 */
return array(
    'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
    'name'=>'My Console Application',

    // preloading 'log' component
    //'preload'=>array('log'),

    'import'=>array(
        'application.models.*',
        'application.components.*',
    ),



    // application components
    'components'=>array(
//        'db'=>array(
//            'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
//        ),

        //отправка смс сообщений в разных частях системы
        'sms'=>array(
            'class'=>'application.components.sms.YiiSms',
            'login'=>'amazing1',
            'pass'=>'zx78op01',
            'port'=>80,
            'host'=>'api.smsfeedback.ru',
            'salt'=>'159753',// соль для шифрования кода смс, в сессии юзера
        ),

        'db'=>array(
            'connectionString' => 'mysql:host=localhost;dbname=secret',
            'emulatePrepare' => true,
            'username' => 'root',
            'password' => 'root',
            'charset' => 'utf8',
            'tablePrefix' => 'tbl_',
            'schemaCachingDuration' => 1000,
            // включаем профайлер
            //'enableProfiling'=>true,
            // показываем значения параметров
            //'enableParamLogging' => true,
        ),

        'cache'=>array(
            'class'=>'system.caching.CFileCache',
        ),
        // uncomment the following to use a MySQL database
        /*
        'db'=>array(
            'connectionString' => 'mysql:host=localhost;dbname=testdrive',
            'emulatePrepare' => true,
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
        ),
        */
//        'log'=>array(
//            'class'=>'CLogRouter',
//            'routes'=>array(
//                array(
//                    'class'=>'CFileLogRoute',
//                    'levels'=>'error, warning',
//                ),
//            ),
//        ),
    ),
);