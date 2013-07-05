<?php
/**
 * Created by JetBrains PhpStorm.
 * User: artem
 * Date: 12.06.13
 * Time: 10:43
 * To change this template use File | Settings | File Templates.
 */
?>

<h3>Отправленные приглашения</h3>

<?php

echo CHtml::link('Отправить приглашение','invitations/form');

$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider'=>$dataProvider,
    'id'=>'partner-grid',
    'template'=>'{items}{pager}',
    //'filter'=>$model,
    //'rowCssClassExpression'=>'($data->type_operation==1)?"prihod":"rashod"',
    'columns'=>array(
        array(
            'name'=>'phone',
            'type'=>'raw',
            'value'=>'$data->phone',
            'filter'=>false,
        ),
        array(
            'name'=>'create_at', 'type'=>'raw', 'value'=>'date("d-m-Y H:i:s",$data->create_at)', 'filter'=>false,
        ),
        array(
            'name'=>'status',
            'type'=>'raw',
            'value'=>'$data->statussend',
            'filter'=>false,
        ),
        //Main::convNumber($data->bonuse);
        array(
            'name'=>'invitations_text',
            'type'=>'raw',
            'value'=>'Main::shotString($data->invitations_text)',
            'filter'=>false,
        ),

        array(
            'class'=>'CButtonColumn',
            'visible'=>false,
        ),
    ),
));