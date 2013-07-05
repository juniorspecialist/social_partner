<?php
/**
 * Created by JetBrains PhpStorm.
 * User: artem
 * Date: 24.05.13
 * Time: 10:43
 * To change this template use File | Settings | File Templates.
 */

/*
 * мыло админа аккаунта - alexsashkan@mail.ru
 */
class RobokassaBehavior extends CActiveRecordBehavior {

    private $loginR = 'thesistem';
    private $passR = 'azx78op01';

    // модель, которая работает с балансом юзера
    public $modelPartner = 'Partner';
    // поле модели, в котором хранится баланс юзера
    public $fieldBalance = 'balance';


}