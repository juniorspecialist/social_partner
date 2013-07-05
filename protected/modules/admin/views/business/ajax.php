<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Саня
 * Date: 12.05.13
 * Time: 20:08
 * To change this template use File | Settings | File Templates.
 */
//ФИО- верифицированный номер – почта - статус
$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider'=>$dataProvider,
    'id'=>'ajax-grid',
    //'ajaxUpdate'=>false,
    'template'=>'{items}{pager}',
    //'enableSorting'=>false,
    'columns'=>array(
        array(
            'name'=>'fio',
            'type'=>'raw',
            'value'=>'CHtml::encode($data->fio)',
            'filter'=>false,
        ),
        array(
            'name'=>'phone',
            'value'=>'$data->phone',
            'filter'=>false,
        ),
        array(
            'name'=>'email',
            'type'=>'raw',
            'value'=>'$data->email',
            'filter'=>false,
        ),
        array(
            'name'=>'status',
            'type'=>'raw',
            'value'=>'$data->statuspartner',
            'filter'=>false,
        ),
        array(
            'class'=>'CButtonColumn',
            'visible'=>false,
        ),
    ),
));