<?php
/**
 * Created by JetBrains PhpStorm.
 * User: artem
 * Date: 30.05.13
 * Time: 14:37
 * To change this template use File | Settings | File Templates.
 */
if(!Yii::app()->user->isGuest){
    $this->setPageTitle(Yii::app()->config->getPageTitle('Ошибка при пополнении баланса пользователя'));
}else{
    $this->setPageTitle('Ошибка при пополнении баланса пользователя');
}

?>
<h2>Произошла ошибка при пополнении баланса</h2>
<strong>Пожалуйста свяжитесь с администратором системы</strong>