<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Саня
 * Date: 30.05.13
 * Time: 21:01
 * To change this template use File | Settings | File Templates.
 */
?>

<h3>Заявки на вывод средств:</h3>
<?php

echo CHtml::link('Создать заявку', Yii::app()->createUrl('partner/finance/Outballs'));


$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider'=>$dataProvider,
    'id'=>'partner-grid',
    'template'=>'{items}{pager}',
    //'filter'=>$model,
    'columns'=>array(
        array(
            //'header'=>'ФИО',
            'name'=>'type_cash',
            'type'=>'raw',
            'value'=>'$data->TypeCash',
            'filter'=>false,
        ),
        array(
            'name'=>'create_at',
            //'header'=>'Получатель бонуса(номер счёта)',
            'value'=>'date("d-m-Y H:i:s",$data->create_at)',
            'filter'=>false,
        ),
        array(
            'header'=>'Сумма',
            'value'=>'$data->sum_cash',
        ),
        array(
            //'header'=>'Сумма к выводу',
            'name'=>'status',
            'type'=>'raw',
            'value'=>'$data->StatusCashOut',
            'filter'=>false,
        ),
        array(
            'class'=>'CButtonColumn',
            'visible'=>false,
        ),
    ),
));