<?php
/**
 * Created by JetBrains PhpStorm.
 * User: artem
 * Date: 30.05.13
 * Time: 14:36
 * To change this template use File | Settings | File Templates.
 */

if(!Yii::app()->user->isGuest){
    $this->setPageTitle(Yii::app()->config->getPageTitle('Пополнение баланса пользователя'));
}else{
    $this->setPageTitle('Пополнение баланса пользователя');
}

?>
<h3>Спасибо, ваш баланс успешно пополнен</h3>