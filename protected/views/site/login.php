<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form CActiveForm  */

//$this->pageTitle=Yii::app()->name . ' - Login';
$this->breadcrumbs=array(
	'Login',
);
?>

<h1>Авторизация пользователя</h1>

<div class="form">
<?php

//if(Yii::app()->user->hasFlash('error') || Yii::app()->user->hasFlash('success')){
//    $this->widget('application.extensions.PNotify.PNotify',
//        array(
//            'flash_messages_only' => true,
//            'options'=>array(
//                'closer'=>true,
//                'hide'=>true,
//                'delay'=>3000,
//
//            )
//        )
//    );
//}


$form=$this->beginWidget('CActiveForm', array(
	'id'=>'login-form',
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>

<!--	<p class="note">Fields with <span class="required">*</span> are required.</p>-->

	<div class="row">
		<?php echo $form->labelEx($model,'username'); ?>
		<?php echo $form->textField($model,'username'); ?>
		<?php echo $form->error($model,'username'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'password'); ?>
		<?php echo $form->passwordField($model,'password'); ?>
		<?php echo $form->error($model,'password'); ?>
<!--		<p class="hint">-->
<!--			Hint: You may login with <kbd>demo</kbd>/<kbd>demo</kbd> or <kbd>admin</kbd>/<kbd>admin</kbd>.-->
<!--		</p>-->
	</div>

<!--	<div class="row rememberMe">-->
<!--		--><?php //echo $form->checkBox($model,'rememberMe'); ?>
		<?php //echo $form->label($model,'rememberMe'); ?>
<!--		--><?php //echo $form->error($model,'rememberMe'); ?>
<!--	</div>-->

	<div class="row buttons">
		<?php echo CHtml::submitButton('Авторизация'); ?>
	</div>

<?php $this->endWidget(); ?>
</div><!-- form -->