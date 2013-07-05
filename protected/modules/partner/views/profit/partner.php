<?php
/**
 * Created by JetBrains PhpStorm.
 * User: artem
 * Date: 13.05.13
 * Time: 17:04
 * To change this template use File | Settings | File Templates.
 */
?>
<h3>Доходы по партнерской программе</h3>

<strong>Заработано всего в Партнерской программе, баллов:</strong> <?=Main::convNumber($model->inComeProfit())?><br>

<strong>Заработано за текущий месяц в Партнерской программе:</strong> <?=Main::convNumber($model->inComeProfit(strtotime($model->startMonth), strtotime($model->endMonth)))?><br>

<strong>Ваш уровень в Партнерской Программе:</strong> <?=$partner->partnerlevel?><br>

<strong>Кол-во рефералов на 1 уровне со статусом "Партнер":</strong> <?=$partner->countChildren(Partner::STATUS_Partner, 1)?><br>

<strong>Кол-во приобретенных Вами Партнерских комплектов:</strong> <?=$partner->partnershipCount?><br>

<strong>Кол-во баллов активности:</strong> <?=$partner->active_points?><br>

<strong>Кол-во рефералов на 2-10 уровнях:</strong> <?=$partner->countChildrenInIntervalLevels(2, 10)?><br>

<?

echo '<br>';
echo CHtml::link('Показать таблицу с данными','javascript::void(0)', array('id'=>'tbl_data'));

?>
<div class="substratre" id="subdataProvider" style="display: none">
    <div class="modal_win" id="dataProvider">
        <?php
//        $this->renderPartial('_table_profit_partner', array(
//            'dataProvider'=>$dataProvider,
//            'model'=>$model
//        ));
        ?>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {

        // клик по ссылке - для показа таблицы по доходам партнерской программы
        $(document).on('click','#tbl_data', function(event){

            event.preventDefault();

            $('#subdataProvider').show();

            $.ajax({
                url: "AjaxProfitTable",
                'type':'POST',
                beforeSend: function (xhr) {
                    //xhr.overrideMimeType("text/plain; charset=x-user-defined");
                    $('#dataProvider').append('<img src="/images/ajax-loader.gif">Подождите происходит обработка запроса...');
                }
            })
            .done(function (data) {
                $('#dataProvider').html(data);
            });
        });

        $(document).on('click', '#close_modal', function(event){
            event.preventDefault();
            $('#subdataProvider').hide();
        });
    });
</script>