<?php
/**
 * Created by JetBrains PhpStorm.
 * User: artem
 * Date: 11.06.13
 * Time: 17:38
 * To change this template use File | Settings | File Templates.
 */
$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider'=>$dataProvider,
    'id'=>'ajax-grid',
    'filter'=>$model,
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