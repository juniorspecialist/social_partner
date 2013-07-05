<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form CActiveForm  */


$this->breadcrumbs=array(
	'Login',
);
?>

<div class="form" id="admin_login">
    <h1>Авторизация администратора</h1>
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'admin-form',
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>
        <?php if($showFileds){?>
            <div id="show_sms" ">
                <div class="row">
                    <?php echo $form->labelEx($model,'smsCode'); ?>
                    <?php echo $form->textField($model,'smsCode'); ?>
                    <?php echo $form->error($model,'smsCode'); ?>
                    <?php
                        echo $form->hiddenField($model,'username');
                        echo $form->hiddenField($model,'password');
                    ?>
                </div>
            </div>
        <?php }else{?>

            <div class="row">
                <?php echo $form->labelEx($model,'username'); ?>
                <?php echo $form->textField($model,'username'); ?>
                <?php echo $form->error($model,'username'); ?>
            </div>

            <div class="row">
                <?php echo $form->labelEx($model,'password'); ?>
                <?php echo $form->passwordField($model,'password'); ?>
                <?php echo $form->error($model,'password'); ?>
            </div>
        <?php } ?>

<!--	<div class="row buttons">-->
<!--		--><?php //echo CHtml::submitButton('Авторизация'); ?>
<!--	</div>-->
    <?

    echo CHtml::ajaxLink(
        ($showFileds)?"Подтвердить СМС":"Авторизация",
            Yii::app()->createUrl('admin/profil/login'),
        array( // ajaxOptions
            'type' =>'POST',
            'beforeSend' => "function(request){
            }",
            'success' => "function(data){
                // handle return data
                if(data=='ok'){
                    location.href='/admin/profil/update';
                }else{
                    $('#admin_login').html(data);
                }
            }",
            'data' => 'js:$("#admin-form").serialize()',
        ),
        array( //htmlOptions
        'href' => Yii::app()->createUrl('admin/profil/login'),
        //'class' => $class
         'id'=>'link_post',
        )
    );
    ?>

<?php $this->endWidget(); ?>
</div><!-- form -->

<?php

// подключаем либу для блокирования экрана на время выполнения AJAX запроса
Yii::app()->clientScript->registerScriptFile(
    Yii::app()->baseUrl . '/js_plugins/jquery.blockUI.js',
    CClientScript::POS_END
);


Yii::app()->clientScript->registerScript('loading', '
    $("#loading").bind("ajaxSend", function(){
        $("#admin_login").block({ message: null });
        $(this).show();
    }).bind("ajaxComplete", function(){
            $("#admin_login").unblock();
            $(this).hide();
        });
    ', CClientScript::POS_READY);
?>

<div id="loading" style="display:none;">Подождите, происходит отправка смс-кода...</div>

<script type="text/javascript">
    $(document).ready(function(){
        $("#LoginPartner_password").keypress(function(e){
            if(e.keyCode==13){
                e.preventDefault();
                //нажата клавиша enter - здесь ваш код
                $('#link_post').click();
            }
        });
        $("#LoginPartner_username").keypress(function(e){
            if(e.keyCode==13){
                e.preventDefault();
                //нажата клавиша enter - здесь ваш код
                $('#link_post').click();
            }
        });
        $("#Login_smsCode").keypress(function(e){
            if(e.keyCode==13){
                e.preventDefault();
                //нажата клавиша enter - здесь ваш код
                $('#link_post').click();
            }
        });
    });
</script>
