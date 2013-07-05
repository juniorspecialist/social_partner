<?php
/**
 * Created by JetBrains PhpStorm.
 * User: artem
 * Date: 28.05.13
 * Time: 12:48
 * To change this template use File | Settings | File Templates.
 */
?>
<h3>Смена спонсора у выбранного пользователя</h3>

<div class="form">
    <?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'login-form',
        'enableClientValidation'=>true,
        'clientOptions'=>array(
            'validateOnSubmit'=>true,
        ),
    )); ?>

    <!--	<p class="note">Fields with <span class="required">*</span> are required.</p>-->



    <?php
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
    ?>

    <div class="row">
        <?php echo $form->labelEx($model,'partner'); ?>
        <?php echo $form->textField($model,'partner'); ?>
        <?php echo $form->error($model,'partner'); ?>
        <p class="hint">Укажите номер счета партнёра, которому необходимо изменить спонсора</p>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'sponsor_new'); ?>
        <?php echo $form->textField($model,'sponsor_new'); ?>
        <?php echo $form->error($model,'sponsor_new'); ?>
        <p class="hint">Укажите номер счета нового спонсора</p>
    </div>


    <div class="row buttons">
        <?php echo CHtml::submitButton('Изменить спонсора'); ?>
    </div>

    <?php $this->endWidget(); ?>
</div><!-- form -->