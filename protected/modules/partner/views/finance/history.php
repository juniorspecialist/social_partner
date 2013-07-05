<?php
/**
 * Created by JetBrains PhpStorm.
 * User: artem
 * Date: 29.05.13
 * Time: 12:38
 * To change this template use File | Settings | File Templates.
 */
?>

<h3>История счета</h3>
<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider'=>$dataProvider,
    'id'=>'partner-grid',
    'template'=>'{items}{pager}',
    'filter'=>$model,
    'rowCssClassExpression'=>'($data->type_operation==1)?"prihod":"rashod"',
    'columns'=>array(
        array(
            'name'=>'type_operation',
            'type'=>'raw',
            'value'=>'$data->TypeOperation',
            'filter'=>false,
        ),
        array(
            'name'=>'partner_id',
            'header'=>'Получатель бонуса(номер счёта)',
            'value'=>'$data->partner_id',
            'filter'=>false,
        ),
        array(
            'name'=>'bonuse',
            'type'=>'raw',
            'value'=>'Main::convNumber($data->bonuse)',
            'filter'=>false,
        ),
        array(
            'name'=>'destination',
            'type'=>'raw',
            'value'=>'$data->DestinationPay',
            'filter'=>false,
        ),
        array(
            'name'=>'create_at', 'type'=>'raw', 'value'=>'date("d-m-Y H:i:s",$data->create_at)', 'filter'=>false,
        ),
        array(
            'header'=>'Отправитель бонуса(номер счета)','name'=>'bonus_sender', 'type'=>'raw', 'value'=>'$data->bonus_sender', 'filter'=>false,
        ),
        array(
            'class'=>'CButtonColumn',
            'visible'=>false,
        ),
    ),
));