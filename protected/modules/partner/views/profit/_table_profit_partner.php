<?php
/**
 * Created by JetBrains PhpStorm.
 * User: artem
 * Date: 06.06.13
 * Time: 15:10
 * To change this template use File | Settings | File Templates.
 */
echo "*информация в таблице приведена на момент после совершения транзакции<br>";

echo '<strong>'.CHtml::link('Закрыть','javascript::void(0)', array('style'=>'margin:0 auto; margin-right:-80%;', 'id'=>'close_modal')).'</strong>';

$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider'=>$dataProvider,
    'id'=>'partner-grid__',
    'template'=>'{items}{pager}',
    'filter'=>$model,
    //'ajaxupdate'
    'columns'=>array(
        array(
            'name'=>'destination_account',
            'type'=>'raw',
            'value'=>'$data->destination_account',
            'filter'=>false,
        ),
        array(
            'name'=>'point',
            'value'=>'$data->point',
            'filter'=>false,
        ),
        array(
            'name'=>'sender_account',
            'type'=>'raw',
            'value'=>'$data->sender_account',
            'filter'=>false,
        ),
        array(
            'name'=>'has_partners',
            'type'=>'raw',
            'value'=>'$data->has_partners',
            'filter'=>false,
        ),
        array(
            'name'=>'has_personal_partners', 'type'=>'raw', 'value'=>'$data->has_personal_partners', 'filter'=>false,
        ),
        array(
            'name'=>'active_points', 'type'=>'raw', 'value'=>'$data->active_points', 'filter'=>false,
        ),
        array(
            'name'=>'partner_level',
            'type'=>'raw',
            'value'=>'Partner::getpartnerlevel_("$data->partner_level")',
            //'value'=>array($model,'getPartnerLevelDestination'),
            'filter'=>false,
        ),
        array(
            'name'=>'active_points_sender', 'type'=>'raw', 'value'=>'$data->active_points_sender', 'filter'=>false,
        ),
        array(
            'name'=>'partner_level_sender',
            'type'=>'raw',
            //'value'=>'$data->senderAccount->partnerlevel',
            //'value'=>'$data->senderAccount->getpartnerlevel($data->partner_level_sender)',
            'value'=>array($model, 'getPartnerLevelSender'),
            'filter'=>false,
        ),
        array(
            'name'=>'level_cooperator', 'type'=>'raw', 'value'=>'$data->level_cooperator', 'filter'=>false,
        ),
        array(
            'name'=>'create_at', 'type'=>'raw', 'value'=>'date("d-m-Y H:i:s",$data->create_at)', 'filter'=>false,
        ),
        array(
            'name'=>'bonus_from_level1', 'type'=>'raw', 'value'=>'$data->bonus_from_level1', 'filter'=>false,
        ),
        array(
            'name'=>'bonus_from_other_levels', 'type'=>'raw', 'value'=>'$data->bonus_from_other_levels', 'filter'=>false,
        ),
        array(
            'class'=>'CButtonColumn',
            'visible'=>false,
        ),
    ),
));
?>
