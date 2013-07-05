<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Саня
 * Date: 29.05.13
 * Time: 21:57
 * To change this template use File | Settings | File Templates.
 */
?>

<div class="form"  id="transfer">

    <h3>Перевод баллов:</h3>

    <?php

    $this->setPageTitle(Yii::app()->config->getPageTitle('Перевод баллов'));

    //if(Yii::app()->user->hasFlash('success')):

            //$this->widget('ext.jgrowl.Jgrowl',array('message'=>Yii::app()->user->getFlash('transfer')));
    if(Yii::app()->user->hasFlash('error') || Yii::app()->user->hasFlash('success')){
        $this->widget('application.extensions.PNotify.PNotify',
            array(
                'flash_messages_only' => true,
                'options'=>array(
                    'closer'=>true,
                    'hide'=>true,
                    'delay'=>3000,
                )
            )
        );
    }

    $form=$this->beginWidget('CActiveForm', array(
        'id'=>'transfer-form',
        'enableClientValidation'=>true,
        'clientOptions'=>array(
            'validateOnSubmit'=>true,
        ),
    ));
    ?>

    <?php if($showFileds){?>
        <div id="show_sms" ">
            <div class="row">
                <?php echo $form->labelEx($model,'smsCode'); ?>
                <?php echo $form->textField($model,'smsCode'); ?>
                <?php echo $form->error($model,'smsCode'); ?>
                <?php
                    echo $form->hiddenField($model,'to_partner');
                    echo $form->hiddenField($model,'sum_transfer');
                ?>
            </div>
        </div>
    <?php }else{?>
        <div class="row">
            <?php echo $form->labelEx($model,'to_partner'); ?>
            <?php echo $form->textField($model,'to_partner',array('size'=>20,'maxlength'=>20)); ?>
            <?php echo $form->error($model,'to_partner'); ?>
        </div>

        <div class="row">
            <?php echo $form->labelEx($model,'sum_transfer'); ?>
            <?php echo $form->textField($model,'sum_transfer',array('size'=>20,'maxlength'=>20)); ?>
            <?php echo $form->error($model,'sum_transfer'); ?>
        </div>

    <?php } ?>

    <?
        echo CHtml::ajaxLink(
            ($showFileds)?"Подтвердить СМС":"Перевести баллы",
            //Yii::app()->createUrl('admin/profil/login'),
            '',
            array( // ajaxOptions
                'type' =>'POST',
                'beforeSend' => "function(request){
                    $('#transfer-form').addClass('loading');
                }",
                'success' => "function(data){

                    $('#transfer-form').removeClass('loading');

                    // handle return data
                    if(data=='ok'){
                        location.href='';
                    }else{
                        $('#transfer').html(data);
                    }
                }",
                'data' => 'js:$("#transfer-form").serialize()',
            ),
            array( //htmlOptions
                'href' => Yii::app()->createUrl('profil/login'),
                'id' => 'go',
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
        $("#transfer-form").block({ message: null });
        $(this).show();
    }).bind("ajaxComplete", function(){
        $("#transfer-form").unblock();
        $(this).hide();
    });
    ', CClientScript::POS_READY);
?>

<div id="loading" style="display:none;">Подождите, происходит отправка смс-кода...</div>


<script type="text/javascript">
    $(document).ready(function(){
        $("#TransferModel_sum_transfer").keypress(function(e){
            if(e.keyCode==13){
                e.preventDefault();
                //нажата клавиша enter - здесь ваш код
                $('#go').trigger('click');
            }
        });
        $("#TransferModel_to_partner").keypress(function(e){
            if(e.keyCode==13){
                e.preventDefault();
                //нажата клавиша enter - здесь ваш код
                $('#go').trigger('click');
            }
        });
        $('#TransferModel_smsCode').keypress(function(e){
            if(e.keyCode==13){
                e.preventDefault();
                //нажата клавиша enter - здесь ваш код
                $('#go').trigger('click');
            }
        });
    });
</script>