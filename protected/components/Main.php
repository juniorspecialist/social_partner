<?php
/**
 * Created by JetBrains PhpStorm.
 * User: artem
 * Date: 03.06.13
 * Time: 13:57
 * To change this template use File | Settings | File Templates.
 */

class Main {

    /*
     * преобразовываем число в формат - 2 знака после точки
     */
    public static function convNumber($number){

        //echo 'type='.gettype($number).'<br>';

//        if(gettype($number)=='string'){
//            return $number;
//        }else{
        //$result = number_format($number, 2, '.', ' ');

        $number = floatval($number);

        if(is_integer($number) || is_float($number)){
            return number_format($number, 2, '.', ' ');
        }else{
            return $number;
        }

//        try {
//            throw new Exception();
//
//
//            return $number;
//
//        } catch (Exception $e) {
//            return number_format($number, 2, '.', ' ');
//        }


        //return $result;
    }

    /*
     * отображаем часть предложения, обрезаем текст
     * $strlen- длина строки, по словам, которую получаем
     */
    public static function shotString($data_string, $strlen = 120){

        $words = explode(' ', $data_string);

        $result = '';

        foreach($words as $word){
            $result.=$word.' ';
        }

        return $result;
    }

    /*
     * получаем заголовок ниформациоорный, для вывода баланса по текущему юзеру
     */
    public static function labelBalanceMenu(){

        $label = '';
        $balance = Partner::getBalance(Yii::app()->user->id);

        if(!Yii::app()->user->isGuest){
            if(Yii::app()->user->role==Partner::ROLE_ADMIN){
                $label = 'Добро пожаловать, '.Yii::app()->user->fio.'. Ваш баланс '.Main::convNumber($balance).' '.Yii::t('app', 'balls', array($balance));
            }else{
                $label = 'Баланс '.Main::convNumber($balance).' '.Yii::t('app', 'balls', array($balance));
            }

        }

        return $label;
    }
}