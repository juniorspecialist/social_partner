<?php
/**
 * Created by JetBrains PhpStorm.
 * User: artem
 * Date: 29.05.13
 * Time: 16:14
 * To change this template use File | Settings | File Templates.
 */
?>
<h3>Ввод наличных средств:</h3>
<style>
div.del_link{
    color: dodgerblue;
    float: right;
    margin-top: -50px;
    width: 30px;;
}
</style>

<?php if(Yii::app()->user->hasFlash('inputmoney')): ?>
    <div class="flash-success">
        <?php echo Yii::app()->user->getFlash('inputmoney'); ?>
    </div>
<?php endif; ?>

<div class="form" id="refill">
    <?php
    $form=$this->beginWidget('CActiveForm', array(
        'id'=>'inputmoney',
        'enableClientValidation'=>true,
        'clientOptions'=>array(
            'validateOnSubmit'=>true,
        ),
    ));

    $this->widget('ext.jqrelcopy.JQRelcopy',
        array(
            'id' => 'copylink',
            'removeText' => 'Удалить',
            //'jsBeforeNewId' => "alert(this.attr('id'));",
            //add the datapicker functionality to the cloned datepicker with the same options
            //'jsAfterNewId' => JQRelcopy::afterNewIdDatePicker($datePickerConfig),
        )
    );
?>
    <a id="copylink" href="#" rel=".copy">Добавить ещё строк ?</a>

    <div class="row copy" style="width: 350px; height: 25px">
        <table style="width: 300px; ">
            <tr>
                <td><?php echo CHtml::label('Сумма',''); ?></td>
                <td><?php echo CHtml::textField('sum[]','', array('size'=>6, 'class'=>'validate[required,custom[onlyNumberSp]]')); ?></td>
                <td><?php echo CHtml::label('ID',''); ?></td>
                <td><?php echo CHtml::textField('id[]',"",array('class'=>'validate[required,custom[onlyNumberSp]]','size'=>5)); ?></td>
            </tr>
        </table>
    </div>

<!--    <div class="row copy">-->
<!--        --><?php //echo $form->labelEx($model,'sum'); ?>
        <?php //echo $form->textField($model,'sum'); ?>
        <?php //echo $form->error($model,'sum'); ?>
<!---->
<!--        --><?php //echo $form->labelEx($model,'partner_id'); ?>
        <?php //echo $form->textField($model,'partner_id'); ?>
        <?php //echo $form->error($model,'partner_id'); ?>
<!---->
<!--    </div>-->

    <div class="row buttons"><?php echo CHtml::submitButton('Зачислить'); ?></div>

    <?php $this->endWidget(); ?>

</div><!-- form -->

<script src="/js_plugins/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
<script src="/js_plugins/languages/jquery.validationEngine-ru.js" type="text/javascript" charset="utf-8"></script>
<link rel="stylesheet" type="text/css" href="/css/validationEngine.jquery.css" />
<script type="text/javascript">
    jQuery(document).ready(function(){
        $("#inputmoney").validationEngine();
    });
</script>