<?php /* @var $this Controller */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />

	<!-- blueprint CSS framework -->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
	<![endif]-->

	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />

	<title><?php
            echo CHtml::encode($this->pageTitle);
        ?></title>
</head>

<body>

<div class="container" id="page">

	<div id="header">
		<div id="logo"><?php echo CHtml::encode(Yii::app()->name); ?></div>
	</div><!-- header -->

	<div id="mainmenu">
		<?php
        /*
        $this->widget('zii.widgets.CMenu',array(
			'items'=>array(
				array('label'=>'Мой бизнесс', 'url'=>array('/partner/business/'), 'items'=>array(
                    array('label'=>'Личное развитие', 'url'=>array('/partner/business/')),
                    array('label'=>'Мой бизнесс', 'url'=>array('/partner/business/')),
                    array('label'=>'Развитие бизнесса', 'url'=>array('/partner/business/')),
                    array('label'=>'Доход', 'url'=>array('/partner/business/')),
                    array('label'=>'Структура пользователя', 'url'=>array('/partner/business/')),
                )),
				array('label'=>'Мои доходы', 'url'=>array('/partner/page', 'view'=>'about')),
				array('label'=>'Contact', 'url'=>array('/site/contact')),
				array('label'=>'Login', 'url'=>array('/site/login'), 'visible'=>Yii::app()->user->isGuest),
				array('label'=>'Logout ('.Yii::app()->user->name.')', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest)
			),
		));*/
        if(!Yii::app()->user->isGuest){
            //меню юзера
            if(Yii::app()->user->role==Partner::ROLE_USER){
                $this->widget('ext.cssmenu.CssMenu',array(
                    'items'=>array(
                        //array('label'=>'Home', 'url'=>array('site/index')),
                        array('label'=>'Мой бизнес',  'items'=>array(
                            array('label'=>'Личное развитие', 'url'=>array('/partner/business/personal')),
                            array('label'=>'Развитие бизнеса', 'url'=>array('/partner/business/progress')),
                            array('label'=>'Мой доход', 'url'=>array('/partner/business/profit')),
                            array('label'=>'Моя структура', 'url'=>array('/partner/business/structure')),
                        ),
                        ),
                        array(
                            'label'=>'Мои доходы', 'items'=>array(
                            array('label'=>'Доходы по Партнерской программе', 'url'=>array('/partner/profit/partner')),
                        )
                        ),
                        array(
                            'label'=>'Мой счет', 'items'=>array(
                            array('label'=>'Пополнение', 'url'=>array('/partner/finance/refill')),
                            array('label'=>'Перевод баллов внутри системы', 'url'=>array('/partner/finance/transfer')),
                            array('label'=>'Вывод', 'url'=>array('/partner/finance/outballslist')),
                            array('label'=>'История счета', 'url'=>array('/partner/finance/history')),
                        )
                        ),
                        array(
                            'label'=>Main::labelBalanceMenu(),
                            //'label'=>'Добро пожаловать '.Yii::app()->user->fio.', ваш баланс: '.Partner::getBalance(Yii::app()->user->id).'(бал.)',
                            'visible'=>!Yii::app()->user->isGuest,
                            'url'=>array('/partner/finance/refill'),

                        ),
                        array('label'=>'Выйти', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest)

                        //array('label'=>'Login', 'url'=>array('site/login'), 'visible'=>Yii::app()->user->isGuest),
                    )
                ));
            }
            //меню админа
            if(Yii::app()->user->role==Partner::ROLE_ADMIN){
                $this->widget('ext.cssmenu.CssMenu',array(
                    'items'=>array(

                            array('label'=>'Редактировать профиль', 'url'=>array('admin/profil/update'), 'visible'=>!Yii::app()->user->isGuest),

                            //array('label'=>'Home', 'url'=>array('site/index')),
                            array('label'=>'Мой бизнес',  'items'=>array(
                                array('label'=>'Развитие бизнеса', 'url'=>array('/admin/business/progress')),
                                array('label'=>'Мой доход', 'url'=>array('/admin/business/profit')),
                                array('label'=>'Моя структура', 'url'=>array('/admin/business/structure')),
                                array('label'=>'Смена спонсора', 'url'=>array('/admin/business/change_sponsor')),
                            ),
                        ),
                        array(
                            'label'=>'Доход', 'items'=>array(
                                array('label'=>'Партнерская программа', 'url'=>array('/admin/profit/partner')),
                            )
                        ),
                        array(
                            'label'=>'Мой счет', 'items'=>array(
                                //array('label'=>'Пополнение', 'url'=>array('/admin/finance/')),
                                array('label'=>'Перевод баллов внутри системы', 'url'=>array('/admin/finance/transfer')),
                                //array('label'=>'Вывод', 'url'=>array('/admin/finance/outcash')),
                                array('label'=>'История счета', 'url'=>array('/admin/finance/history')),
                            //
                                array('label'=>'Движение средств в системе', 'url'=>array('/admin/finance/action_finance')),

                                array('label'=>'Ввод наличных средств', 'url'=>array('/admin/finance/inputballs')),
                                array('label'=>'Вывод средств', 'url'=>array('/admin/finance/outputballs')),
                           )
                        ),
                        array(
                            'label'=>CHtml::link('Социальная сеть',Yii::app()->params['url_social'], array('style'=>'margin-top:-10px')),
                            'visible'=>!Yii::app()->user->isGuest,
                            //'url'=>,
                        ),

                        array(
                            'label'=>Main::labelBalanceMenu(),
                            'visible'=>!Yii::app()->user->isGuest,
                            'url'=>array('/admin/finance/transfer'),
                        ),
                        array('label'=>'Выйти', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest)


                    )
                ));
            }

        }
        ?>
	</div><!-- mainmenu -->
	<?php if(isset($this->breadcrumbs)):?>
		<?php
		 /*
		$this->widget('zii.widgets.CBreadcrumbs', array(
			'links'=>$this->breadcrumbs,
		));*/
		?><!-- breadcrumbs -->
	<?php endif?>

	<?php echo $content; ?>

<!--	<div class="clear"></div>-->
<!---->


</div><!-- page -->

</body>
</html>
