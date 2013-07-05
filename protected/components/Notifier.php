<?php
/**
 * Created by JetBrains PhpStorm.
 * User: artem
 * Date: 28.05.13
 * Time: 9:20
 * To change this template use File | Settings | File Templates.
 */

// Класс-оповещатель
// Рассылает почту при различных событиях
/*
class Notifier {

    function adminAuth($event){

        //отправляем уведомление на почту админа об попытке авторизации
        $message = new YiiMailMessage('Авторизация в админке системы');
        //$message->view = 'registrationFollowup';
        //userModel is passed to the view
        $message->setBody('Кто-то пытался получить доступ к админскому аккаунту "Системы" в '.date('d-m-Y H:i:s').', с IP адреса '.Yii::app()->request->getUserHostAddress(), 'text/html');
        $message->addTo(Yii::app()->params['adminEmail']);
        $message->from = Yii::app()->params['adminEmail'];
        Yii::app()->mail->send($message);
        //echo 'asdfasdfsdafdsf';
    }
}*/