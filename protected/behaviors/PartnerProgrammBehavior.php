<?php
/**
 * Created by JetBrains PhpStorm.
 * User: artem
 * Date: 14.05.13
 * Time: 14:59
 * To change this template use File | Settings | File Templates.
 */

class PartnerProgrammBehavior extends CActiveRecordBehavior {

    public $partnerModel = 'Partner';

    public $_partner_model;

    /*
     * основной метод покупки партнёрского комплекта - статуса
     * ВАлидация на наличие средств, уже была проведена, записываем все события и производим списывание с баланса купившего и фиксируем факт покупки
     * $who_buy - кто покупает комплект
     * $for_whom - для кого покупается комплект
     */
    public function buyPartnerStatus($who_buy, $for_whom){

    }

    /*
     * шаг-1
     * обновим
     */
}