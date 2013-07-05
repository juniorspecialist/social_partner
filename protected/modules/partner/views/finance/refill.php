<?php
/**
 * Created by JetBrains PhpStorm.
 * User: artem
 * Date: 29.05.13
 * Time: 14:46
 * To change this template use File | Settings | File Templates.
 */
?>
<div class="form" id="refill">
    <h3>Пополнение баланса:</h3>
    <?php
    $form=$this->beginWidget('CActiveForm', array(
        'id'=>'cashout-form',
        'enableClientValidation'=>true,
        'action'=>$robokassa->UrlForm,
        'clientOptions'=>array(
            'validateOnSubmit'=>true,
        ),
    ));
    ?>
    <table style="width: 550px;">
        <tr>
            <td colspan="3"><?php //echo $form->errorSummary($model); ?></td>
        </tr>
        <tr>
            <td><?php echo $form->labelEx($model,'amount'); ?></td>
            <td>
                <?php echo $form->textField($model,'amount',array('size'=>20,'maxlength'=>20)); ?>
                <?php echo $form->error($model,'amount'); ?>
            </td>
            <td>
                <div class="row buttons">
                    <?php
                        echo CHtml::ajaxButton('Пополнить',
                            '',
                            array(
                                'type'=> 'POST',
                                'data'=>'js:$("#cashout-form").serialize()',
                                'error'=>'js:function(){}',
                                'beforeSend'=>'js:function(){}',
                                'success'=>'js:function(data){
                                    $("#refill").html(data);
                                }',
                                'complete'=>'js:function(){
                                    if($("#robokassa-form").length){
                                        $("#robokassa-form").submit();
                                        return true;
                                    }else{
                                        return false;
                                    }
                                }',
                            ),
                            array(
                                'id'=>'refil_btn',
                            )
                        );
                    ?>
                </div>
            </td>
        </tr>
    </table>
    <p>*1 балл – 1 RUB</p>

    <?php $this->endWidget(); ?>

    <div id="robokassa" style="display: none"><?echo $robokassa_form;?></div>

<!--    --><?php //} ?>
</div><!-- form -->
<script type="text/javascript">
    $(document).ready(function(){
        $("#Billings_amount").keypress(function(e){
            if(e.keyCode==13){
                e.preventDefault();
                //нажата клавиша enter - здесь ваш код
                $('#refil_btn').click();
            }
        });
    });
</script>

