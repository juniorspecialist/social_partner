<?php
/**
 * Created by JetBrains PhpStorm.
 * User: artem
 * Date: 12.06.13
 * Time: 11:37
 * To change this template use File | Settings | File Templates.
 */
?>
<h3>Отправить приглашения</h3>

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
}?>

<div class="form">
    <?php
    $form=$this->beginWidget('CActiveForm', array(
        'id'=>'invitation-form',
        'enableClientValidation'=>true,
        'clientOptions'=>array(
            'validateOnSubmit'=>true,
        ),
    ));
    ?>

    <?php echo $form->errorSummary($model); ?>

    <div class="row">
        <?php echo $form->labelEx($model,'invitations_text'); ?>
        <?php echo $form->textArea($model,'invitations_text',array('rows'=>6, 'cols'=>50)); ?>
        <?php echo $form->error($model,'invitations_text'); ?>
    </div>


    <div class="row">
        <?php echo $form->labelEx($model,'phoneList'); ?>
        <?php echo $form->textArea($model,'phoneList',array('rows'=>6, 'cols'=>50)); ?>
        <?php echo $form->error($model,'phoneList'); ?>
    </div>


    <div class="row buttons">
        <?php echo CHtml::submitButton('Отправить приглашения'); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->
