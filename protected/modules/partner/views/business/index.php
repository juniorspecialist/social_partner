<?php
/**
 * Created by JetBrains PhpStorm.
 * User: artem
 * Date: 07.05.13
 * Time: 18:28
 * To change this template use File | Settings | File Templates.
 */
$this->widget('zii.widgets.jui.CJuiTabs', array(
        'tabs'=>array(
            //'Static tab'=>'Static content',
            //'Личное развитие»'=>array('ajax'=>array('personal','view'=>'_content2')),

            'Личное развитие'=>array('ajax'=>array('personal')),

            'Развитие бизнеса'=>array('ajax'=>array('progress')),
            //'Развитие бизнеса'=>$this->renderPartial('progress',null,true),

            'Доход'=>array('ajax'=>array('profit')),

            'Структура пользователя'=>$this->renderPartial('structure',null,true),
        ),
        'options'=>array(
            'collapsible'=>false,
            'selected'=>0,
        ),
        'htmlOptions'=>array(
            //'style'=>'width:500px;'
        ),
    )
);