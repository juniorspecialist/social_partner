<?php
/**
 * Created by JetBrains PhpStorm.
 * User: artem
 * Date: 08.05.13
 * Time: 14:33
 * To change this template use File | Settings | File Templates.
 */
$this->setPageTitle(Yii::app()->config->getPageTitle('Развитие бизнеса пользователя'));
?>
<h3>Развитие бизнеса</h3>
<!--<strong>Кол-во занятых* сотрудников:</strong>--><?//=$data->childCountPartner?><!-- <br>-->
<div id="counters">
    <strong>Кол-во сотрудников всего(участников и партнёров): </strong>
    <?
        echo ($childCountMember+$childCountPartner);
        if(($childCountMember+$childCountPartner)>0){
            echo $data->getAjaxlink('Подробнее'.$data->arrow,
                $this->createUrl('/partner/business/ajaxtbl'),
                ''
                //array('Partner[type]'=>'all')
            );
        }
    ?> <br>
    <strong>Кол-во сотрудников в статусе Участник: </strong>
    <?
        echo $childCountMember;
        if($childCountMember>0){
            echo $data->getAjaxlink('Подробнее'.$data->arrow,$this->createUrl('/partner/business/ajaxtbl'), array('Partner[type]'=>Partner::STATUS_MEMBER));
        }
    ?> <br>
    <strong>Кол-во сотрудников в статусе Партнер: </strong>
    <?
        echo $childCountPartner;

        if($childCountPartner>0){
            echo $data->getAjaxlink('Подробнее'.$data->arrow,$this->createUrl('/partner/business/ajaxtbl'), array('Partner[type]'=>Partner::STATUS_Partner));
        }

    ?> <br>
    <strong>Кол-во «Партнеров» на 1 уровне: </strong>
    <?
        echo $childCountPartnerLevel1;
        if($childCountPartnerLevel1>0){
            echo $data->getAjaxlink('Подробнее'.$data->arrow,$this->createUrl('/partner/business/ajaxtbl'), array('Partner[type]'=>-1));
        }
    ?>
</div>
<?
echo CHtml::link($data->getArrow('left'). ' Вернуться к данным','#', array('id'=>'show_counters','style'=>'display:none',
    'onclick'=>'js:$("#tbl").hide(); $("#counters").show();$("#show_counters").hide();return false;'
    )
)
?>
<!--после клика на детальный просмотр отображаем таблицу с пагинацией, спрятав перед этим данные счётчиков-->
<div id="tbl" style="display: none;">
    <?
        $this->renderPartial('ajax', array('dataProvider'=>$dataProvider));
    ?>
</div>