<?php
/**
 * Created by JetBrains PhpStorm.
 * User: artem
 * Date: 28.05.13
 * Time: 10:29
 * To change this template use File | Settings | File Templates.
 */
?>
<div class="form" >
    <h1>Обновить профиль</h1>
<?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'update-profil',
    'enableClientValidation'=>true,
    'clientOptions'=>array(
        'validateOnSubmit'=>true,
    ),
)); ?>

    <?php if(Yii::app()->user->hasFlash('update')): ?>
        <?php
        $this->widget('ext.jgrowl.Jgrowl',array('message'=>Yii::app()->user->getFlash('update')));
        ?>
    <?php endif; ?>

    <div class="row">
        <?php echo $form->labelEx($model,'email'); ?>
        <?php echo $form->textField($model,'email'); ?>
        <?php echo $form->error($model,'email'); ?>
    </div>

<!--    <div class="row">-->
<!--        --><?php //echo $form->labelEx($model,'phone1'); ?>
        <?php //echo $form->textField($model,'phone1'); ?>
<!--        --><?php //echo $form->error($model,'phone1'); ?>
<!--    </div>-->

    <div class="row">
        <?php echo $form->labelEx($model,'phone1'); ?>
        <?php
        /*
        $this->widget('CMaskedTextField', array(
            'model' => $model,
            'attribute' => 'phone1',
            'mask' => '+7-999-999-9999',
            'placeholder' => '*',
            'completed' => 'function(){console.log("ok");}',
        ));
        */
        echo $form->textField($model,'phone1');
        ?>
        <?php echo $form->error($model,'phone1'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'phone2'); ?>
        <?php
        /*
        $this->widget('CMaskedTextField', array(
            'model' => $model,
            'attribute' => 'phone2',
            'mask' => '+7-999-999-9999',
            'placeholder' => '*',
            'completed' => 'function(){console.log("ok");}',
        ));*/
        echo $form->textField($model,'phone2');
        ?>
        <?php echo $form->error($model,'phone2'); ?>
    </div>


    <div class="row">
        <?php echo $form->labelEx($model,'invitation'); ?>
        <?php echo $form->textArea($model,'invitation',array('rows'=>6, 'cols'=>50));?>
        <?php echo $form->error($model,'invitation'); ?>
    </div>


    <div class="row">
        <?php echo $form->labelEx($model,'password'); ?>
        <?php echo $form->passwordField($model,'password'); ?>
        <?php echo $form->error($model,'password'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'password_new'); ?>
        <?php echo $form->passwordField($model,'password_new'); ?>
        <?php echo $form->error($model,'password_new'); ?>
    </div>


	<div class="row buttons">
		<?php echo CHtml::submitButton('Обновить'); ?>
	</div>
<?php $this->endWidget(); ?>
</div><!-- form -->
