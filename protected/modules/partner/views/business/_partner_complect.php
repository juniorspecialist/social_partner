<?php
/**
 * Created by JetBrains PhpStorm.
 * User: artem
 * Date: 14.05.13
 * Time: 15:55
 * To change this template use File | Settings | File Templates.
 */
?>
<div class = "partner-complekt">
    <table border="2" style="border:1px solid red">
        <tr>
            <td><div class = "partner-complekt-photo"><?=CHtml::image($data->photopath,'Партнерский комплект',array('style'=>'width:80px;height:80px;'))?></div></td>
            <td>
                <div class="partner-complekt-title"><?=$data->title?></div>
                <div class="partner-complekt-desc"><?=$data->getAttributeLabel('desc').' : '.$data->desc?></div>
                <div class="partner-complekt-price">
                    <?=$data->getAttributeLabel('price').' : '.Main::convNumber($data->price)?>  руб.
                    <?// если пользователь партнёр, то не вывод сумму с рег. взносом
                    if($status==Partner::STATUS_MEMBER){ echo '+ '.Main::convNumber(400).' руб. - регистрационный взнос';}
                    ?>
                    <div class="partner-complekt-buy-link">
                        <?
                        echo
                        CHtml::link(
                            'Купить',
                            $this->createUrl('/partner/business/buyPartner'),
//                    array(
//                        'type' => 'POST',
//                        'datatype'=>'json',
//                        // можно спросить до отправки что-то или проверить данные какие-нибудь.
//                        'beforeSend' => "function(request){
//                        }",
//                        'success' => "function(data){
//                            $('#personal_info').html(data);
//                            // обновляем список комплектов, вдруг изменилась цена на комплект для юзера
//                            $.fn.yiiListView.update('partner_komplekts');
//                        }",
//                        'data' => array('id'=>$data->id), // посылаем значения
//                        'cache'=>'false' // если нужно можно закешировать
//                    ),
                            array( // самое интересное
                                'href' => 'javascript: void(0)',// подменяет ссылку на левую
                                'class' => "buy_partner_ship", // добавляем какой-нить класс для оформления
                                'id'=>uniqid(),
                                //'data'=>"{'id':".$data->id."}",
                                'data'=>"id=".$data->id,
                                'style'=>'margin-left:40px;'
                            )
                        );
                        ?>
                    </div>
                </div>
            </td>
        </tr>

    </table>
</div>