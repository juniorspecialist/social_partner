<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.

return array(

    //'theme'=>'bootstrap', // requires you to copy the theme under your themes directory

	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'',

	// preloading 'log' component
	'preload'=>array('log', 'config'),

    // язык поумолчанию
    'sourceLanguage' => 'en_US',
    'language' => 'ru',

    'defaultController'=>'site/login',

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
        'application.components.DToggleColumn',
	),
    'theme'=>'classic',

	'modules'=>array(

        //модуль оплат
        'payment',

        //сделан ввиде модуля, чтобы позднее было проще переносить на другую самописную структуру


        //модуль парнёрки(включает админку и пользовательский функционал)
        'partner'=>array(
            'defaultController' => 'business',
        ),

        // модуль админки  - логин и пароль храним как отдельные настройки системы
        'admin'=>array(
            'defaultController' => 'profil',
        ),

		// uncomment the following to enable the Gii tool
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'1',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1'),
		),

	),

	// application components
	'components'=>array(

        //отправка смс сообщений в разных частях системы
        'sms'=>array(
            'class'=>'application.components.sms.YiiSms',
            'login'=>'amazing1',
            'pass'=>'zx78op01',
            'port'=>80,
            'host'=>'api.smsfeedback.ru',
            'salt'=>'159753',// соль для шифрования кода смс, в сессии юзера
        ),
        // сохранение настроек админа и общего назначения
        'config'=>array(
            'class'=>'application.components.config.DConfig',
            'cache'=>0,
        ),

        // установим некоторые значения - по умолчанию
        'widgetFactory'=>array(
            'widgets'=>array(
                'CLinkPager'=>array(
                    'maxButtonCount'=>5,
                    //'cssFile'=>false,
                    'pageSize'=>100,

                ),
                'CJuiDatePicker'=>array(
                    'language'=>'ru',
                ),
            ),
        ),

        // тип кеширования
        'cache'=>array(
            'class'=>'system.caching.CFileCache',
        ),

		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
            // это значение устанавливается по умолчанию
            'loginUrl'=>array('site/login'),
            'returnUrl'=>array('partner/business/personal'),
		),
		// uncomment the following to enable URLs in path-format
		'urlManager'=>array(
			'urlFormat'=>'path',
			'rules'=>array(
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),
            'showScriptName'=>false,
		),

		//'db'=>array('connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',),
		// uncomment the following to use a MySQL database
		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=secret',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => 'root',
			'charset' => 'utf8',
            'tablePrefix' => 'tbl_',
            //'schemaCachingDuration' => 1000,
            // включаем профайлер
            'enableProfiling'=>true,
            // показываем значения параметров
            'enableParamLogging' => true,
		),

		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'site/error',
		),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
                    'levels'=>'error, warning, trace, profile, info',
				),
				// uncomment the following to show log messages on web pages
				/*array(
					'class'=>'CWebLogRoute',
				),*/
                array(
                    // направляем результаты профайлинга в ProfileLogRoute (отображается
                    // внизу страницы)
                    'class'=>'CProfileLogRoute',
                    'levels'=>'profile',
                    'enabled'=>true,
                ),
			),
		),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'alexsashkan@mail.ru',
        'url_social'=>'http://social.the-system.ru'
	),
);