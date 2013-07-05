<?php
/**
 * Created by JetBrains PhpStorm.
 * User: artem
 * Date: 11.06.13
 * Time: 16:08
 * To change this template use File | Settings | File Templates.
 */
?>
<div class="filters tree form" id="filter_form_tree" style="display: none;">

    <?php
    $form=$this->beginWidget('CActiveForm', array(
        'action'=>Yii::app()->createUrl($this->route),
        'method'=>'GET',
        'id'=>'filter_form_tree_forms',
    ));
    ?>

    <div class="row">
        <?php echo $form->label($model,'id', array('style'=>'width:80px;')); ?>
        <?php echo $form->textField($model,'id',array('size'=>20,'maxlength'=>20)); ?>
    </div>

    <div class="row">
        <?php echo $form->label($model,'fio'); ?>
        <?php echo $form->textField($model,'fio',array('size'=>60,'maxlength'=>60)); ?>
    </div>

    <div class="row">
        <?php echo $form->label($model,'email'); ?>
        <?php echo $form->textField($model,'email',array('size'=>60,'maxlength'=>60)); ?>
    </div>

    <div class="row">
        <?php echo $form->label($model,'status'); ?>
        <?php echo $form->dropDownList($model,'status', $model->StatusList, array('empty'=>'Не выбрано значение')); ?>
    </div>


    <div class="row">
        <?php echo $form->label($model,'partner_level'); ?>
        <?php echo $form->dropDownList($model,'partner_level', $model->PartnerLevelList, array('empty'=>'Не выбрано значение')); ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton('Применить фильтры', array('id'=>'accept_filters')); ?>
        <?php echo CHtml::button('Закрыть', array('id'=>'close_filters')); ?>
        <?php echo CHtml::button('Убрать фильтры', array('id'=>'cancel_filters')); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- search-form -->
