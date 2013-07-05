<?php
/**
 * Created by JetBrains PhpStorm.
 * User: artem
 * Date: 29.05.13
 * Time: 13:04
 * To change this template use File | Settings | File Templates.
 */
?>
<h3>Заявка на вывод средств</h3>

<?php

    if(Yii::app()->user->hasFlash('cashout')): ?>
        <?php $this->widget('ext.jgrowl.Jgrowl',array('message'=>Yii::app()->user->getFlash('cashout')));?>
    <?php endif; ?>

    <div class="form">
        <?php
        $form=$this->beginWidget('CActiveForm', array(
            'id'=>'cashout-form',
            'enableClientValidation'=>true,
            'clientOptions'=>array(
                'validateOnSubmit'=>true,
            ),
        ));
        ?>

        <?php //echo $form->errorSummary($model); ?>

        <div class="row">
            <?php echo $form->labelEx($model,'type_cash'); ?>
            <?php
            echo $form->dropDownList($model,'type_cash', $model->getTypeList());
            /*
            echo CHtml::dropDownList('type_cash', (isset($_POST['type_cash']))?$_POST['type_cash']:'',
                $model->TypeList,
                array(
                    'empty' => '(Выберите способ выплаты',

                )
            );
            */
                //echo $form->textField($model,'type_cash');
            ?>
            <?php echo $form->error($model,'type_cash'); ?>
        </div>

        <div class="row">
            <?php echo $form->labelEx($model,'sum_cash'); ?>
            <?php echo $form->textField($model,'sum_cash'); ?>
            <?php echo $form->error($model,'sum_cash'); ?>
        </div>


        <div class="row">
            <?php echo $form->labelEx($model,'desc'); ?>
            <?php echo $form->textArea($model,'desc',array('rows'=>6, 'cols'=>50)); ?>
            <?php echo $form->error($model,'desc'); ?>
        </div>


        <div class="row buttons">
            <?php echo CHtml::submitButton('Создать заявку'); ?>
        </div>

        <?php $this->endWidget(); ?>

    </div><!-- form -->
