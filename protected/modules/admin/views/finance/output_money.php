<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Саня
 * Date: 29.05.13
 * Time: 22:53
 * To change this template use File | Settings | File Templates.
 */
?>

<h3>Заявки на вывод средств:</h3>
<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider'=>$dataProvider,
    'id'=>'partner-grid',
    'template'=>'{items}{pager}',
    //'filter'=>$model,
    'columns'=>array(
        array(
            'header'=>'ФИО',
            'name'=>'id',
            'type'=>'raw',
            'value'=>'$data->partner->fio',
            'filter'=>false,
        ),
        array(
            'name'=>'id',
            //'header'=>'Получатель бонуса(номер счёта)',
            'value'=>'$data->partner_id',
            'filter'=>false,
        ),
        array(
            'header'=>'Сумма к выводу',
            'type'=>'raw',
            'value'=>'Main::convNumber($data->sum_cash)',
            'filter'=>false,
        ),
        array(
            'header'=>'Способ вывода',
            'type'=>'raw',
            'value'=>'$data->TypeCash',
            'filter'=>false,
        ),
        array(
            'header'=>'Реквизиты', 'type'=>'raw', 'value'=>'$data->desc', 'filter'=>false,
        ),
        // иконка со ссылкой по умолчанию на экшен toggleAction($id, $attribute) текущего контроллера

        /*
        array(
            'class'=>'DToggleColumn',
            // атрибут модели
            'name'=>'status',
            // заголовок столбца
            'header'=>'sdfsdf',
            // запрос подтвердждения (если нужен)
            //'confirmation'=>'Изменить статус заявки?',
            // фильтр
            //'filter'=>false,
            // alt для иконок (так как отличается от стандартного)
            'titles'=>array(1=>'Выведены', 2=>'Не выведены'),
            //'linkUrl'=>false,
            'htmlOptions'=>array('style'=>'width:30px'),
            // иконка для значения 1 или true
            'onImageUrl' => Yii::app()->request->baseUrl . '/images/1369943229_3807.ico',
            // иконка для значения 0 или false
            'offImageUrl' => Yii::app()->request->baseUrl . '/images/1369943408_1851.ico',
        ),
        */

        // иконка с другой ссылкой
        array(
            'class'=>'DToggleColumn',
            'name'=>'status',
            'header'=>'Статус',
            //'filter'=>array(1=>'Не обработана', 2=>'Обработана'),
            'confirmation'=>'Изменить статус заявки?',
            // своя ссылка для переключения состояния
            'linkUrl'=>'Yii::app()->controller->createUrl("toggle", array("id"=>$data->id))',
            // иконка для значения 1 или true
            //'onImageUrl' => Yii::app()->request->baseUrl . '/images/1369943229_3807.ico',
            // иконка для значения 0 или false
            //'offImageUrl' => Yii::app()->request->baseUrl . '/images/1369943408_1851.ico',
            // убираем генерацию ссылки по умолчанию
            //'linkUrl'=>false,

            'htmlOptions'=>array('style'=>'width:100px'),
        ),

        array(
            'class'=>'CButtonColumn',
            'visible'=>false,
        ),
    ),
));