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

<strong>Сумма баллов, внесенная участниками Система для оплаты Партнерских комплектов: </strong><?=Main::convNumber($data['price'])?><br>

<strong>Кол-в проданных Партнёрских комплектов</strong>: <?=$data['count']?><br>

<strong>Кол-во проданных Партнерских комплектов(не именных)</strong>: <?=$data['count_no_name_partner']?><br>

<?
/*
ФИО
ID
Всего потрачено средств в рамках Партнерской Прогарммы
Приобретено Партнерских комплектов
Баллов активности
*/
$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider'=>$dataProvider,
    'id'=>'partner-grid',
    'template'=>'{items}{pager}',
    //'filter'=>$model,
    'columns'=>array(
        array(
            'name'=>'id',
            'header'=>'id',
            'type'=>'raw',
            'value'=>'$data["id"]',
            'filter'=>false,
        ),
        array(
            'header'=>'ФИО',
            'value'=>'$data["fio"]',
            'filter'=>false,
        ),

        array(
            'header'=>'Всего потрачено средств в рамках Партнерской Программы',
            'type'=>'raw',
            'value'=>'($data["count"]>0)?((Main::convNumber($data["count"]*3600)+400)):""',
            'filter'=>false,
        ),

        array(
            'header'=>'Приобретено Партнерских комплектов',
            'type'=>'raw',
            'value'=>'($data["count"]>0)?$data["count"]:""',
            'filter'=>false,
        ),
        array(
            'header'=>'Баллов активности',
            'type'=>'raw',
            'value'=>'($data["active_points"]>0)?$data["active_points"]:""',
            'filter'=>false,
        ),

        array(
            'class'=>'CButtonColumn',
            'visible'=>false,
        ),
    ),
));
?>
<style>
    .grid-view table.items th a{
        font-size: 10px;
    }
</style>