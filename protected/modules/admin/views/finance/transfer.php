<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Саня
 * Date: 29.05.13
 * Time: 21:57
 * To change this template use File | Settings | File Templates.
 */
?>
<h3>Перевод баллов внутри системы:</h3>


<div class="form" id="refill">
    <?php

    $this->setPageTitle(Yii::app()->config->getPageTitle('Перевод баллов внутри системы'));

    if(Yii::app()->user->hasFlash('transfer')): ?>
        <?php $this->widget('ext.jgrowl.Jgrowl',array('message'=>Yii::app()->user->getFlash('transfer')));?>
    <?php endif;


    $form=$this->beginWidget('CActiveForm', array(
        'id'=>'transfer-form',
        'enableClientValidation'=>true,
        'clientOptions'=>array(
            'validateOnSubmit'=>true,
        ),
    ));
    ?>

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

    <div class="row buttons">
        <?php echo CHtml::submitButton('Перевести баллы'); ?>
    </div>

    <?php $this->endWidget(); ?>
</div><!-- form -->




