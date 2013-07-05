<?php
/**
 * Created by JetBrains PhpStorm.
 * User: artem
 * Date: 30.05.13
 * Time: 13:18
 * To change this template use File | Settings | File Templates.
 */

/*
 * класс для работы с робокассой
 *
 * документация - http://www.robokassa.ru/ru/Doc/Ru/Interface.aspx
 */
class Robokassa extends CFormModel {

    /*
     *  MrchLogin - логин Продавца;
        OutSum - стоимость заказа в валюте, выбранной Продавцом через интерфейс администрирования;
        InvId - номер заказа в магазине;
        Desc - описание заказа;
        Shp_item - пользовательский параметр;
        SignatureValue - контрольная сумма MD5 (подпись);
        IncCurrLabel - предлагаемая валюта платежа;
        Culture - язык общения;
        Encoding - кодировка, в которой возвращается HTML-код кассы Java-скриптом;
     */

    private $MrchLogin = 'a19b13';
    private $mrh_pass2 = 'azx78op01';//Пароль #2:[используется интерфейсом оповещения о платеже, XML-интерфейсах]
    private $mrh_pass1 = 'ytrewQ123';//Пароль #1:[используется интерфейсом инициализации оплаты]

    public $mode = 'not_test';

    // сумма заказа// sum of order
    public $OutSum; // сумма пополнения баланса

    public $InvId; //ID счета созданного на пополнение баланса юзера

    // предлагаемая валюта платежа// default payment e-currency
    public $in_curr = "rub";

    // язык// language
    public $culture = "ru";

    // описание заказа// order description
    public $Desc = "Пополнение баланса в личном кабинете";

    public $SignatureValue;//контрольная сумма MD5 - строка представляющая собой 32-разрядное число
    public $SignatureForm;// контрольная сумма, созданная из данных системы, для сравнения с контрольной подписью РОбокассы

    // тип товара// code of goods
    public $shp_item = 2;

    public function rules()
    {
        return array(

            //валидация формы пополнения баланса пользователя
            array('OutSum', 'required', 'on'=>'balance'),
            array('OutSum', 'numerical', 'integerOnly'=>true, 'min'=>1, 'on'=>'balance'),

            // отправка запроса на RESULT URL
            array('OutSum, InvId, SignatureValue', 'required', 'on'=>'result'),
            array('InvId, OutSum', 'numerical', 'integerOnly'=>true, 'on'=>'result'),
            array('SignatureValue', 'length', 'max'=>32, 'on'=>'result'),
            array('SignatureValue', 'isValidateSignature', 'on'=>'result'),
            array('InvId', 'isValidateBilling', 'on'=>'result'),

            // отправка запроса на Success URL
            array('OutSum, InvId, SignatureValue', 'required', 'on'=>'success'),
            array('InvId, OutSum', 'numerical', 'integerOnly'=>true, 'on'=>'success'),
            array('SignatureValue', 'length', 'max'=>32, 'on'=>'success'),
            array('SignatureValue', 'isValidateSignature', 'on'=>'success'),

            // отправка запроса на FAIL
            array('OutSum, InvId', 'required', 'on'=>'fail'),
            array('InvId, OutSum', 'numerical', 'integerOnly'=>true, 'on'=>'fail'),
        );
    }

    /*
     * контрольная сумма, для создания формы отправки запроса на пополнение в РОбокассе
     * $crc  = md5("$mrh_login:$out_summ:$inv_id:$mrh_pass1:Shp_item=$shp_item");
     */
    public function getMySignatureForm(){
        $signature = md5($this->MrchLogin.':'.$this->OutSum.':'.$this->InvId.':'.$this->mrh_pass1.':Shp_item='.$this->shp_item);
        return strtoupper($signature);
    }

    /*
     * проверяем на валидность сигнатуры
     */
    public function isValidateSignature(){
        if(!$this->hasErrors()){

            // в зависимости от сценария - используем свою контрольную  подпись
            $signature = strtoupper($this->getSignature());

            if(strtoupper($this->SignatureValue)!=$signature){
                $this->addError('SignatureValue','Значение контрольных сумм не совпадают');
            }
        }
    }

    /*
     * проверяем на валидацию заявку на пополнение баланса
     * на совпадение суммы+ статуса заявки
     */
    public function isValidateBilling(){
        if(!$this->hasErrors()){
            $sql = 'SELECT * FROM {{billings}} WHERE id=:id';
            $connect = Yii::app()->db;
            $query = $connect->createCommand($sql);
            $query->bindValue(':id', $this->InvId, PDO::PARAM_INT);
            $row = $query->queryRow();

            // проверка суммы
            if($row['amount']!=$this->OutSum){
                $this->addError('OutSum', 'Сумма не соотвествует');
            }

            // проверка статуса заявки
            if($row['status']==Billings::STATUS_PAID){
                $this->addError('InvId', 'Данная заявка на пополнение уже оплачена');
            }
        }
    }

    /*
     * формирование подписи
     * в зависимости от сценария используем своё формирование подписи
     */
    public function getSignature(){

        $signature = '';

        // отправка запроса на RESULT адрес
        //nOutSum:nInvId:sMerchantPass2
        if($this->scenario=='result'){
            $signature = md5($this->OutSum.':'.$this->InvId.':'.$this->mrh_pass2.':Shp_item='.$this->shp_item);
        }

        // для отправки запроса на SuccessURL
        //nOutSum:nInvId:sMerchantPass1
        if($this->scenario == 'success'){
            $signature = md5($this->OutSum.':'.$this->InvId.':'.$this->mrh_pass1);
        }

        return $signature;
    }

    /*
     * формируем URL-адрес отправки формы
     */
    public function getUrlForm(){
        // тестовый режим работы
        if($this->mode=='test'){
            return 'http://test.robokassa.ru/Index.aspx';
        }else{
            return 'https://auth.robokassa.ru/Merchant/Index.aspx';
        }
    }

    /*
     * формирование формы для пополнения баланса
     */
    public function getFormPay(){
        return '<form id="robokassa-form" action="'.$this->urlForm.'" method=POST>".
               "<input type=hidden name=MrchLogin value='.$this->MrchLogin.'>".
               "<input type=hidden name=OutSum value='.$this->OutSum.'>".
               "<input type=hidden name=InvId value='.$this->InvId.'>".
               "<input type=hidden name=Desc value='.$this->Desc.'>".
               "<input type=hidden name=SignatureValue value='.$this->MySignatureForm.'>".
               "<input type=hidden name=Shp_item value='.$this->shp_item.'>".

               "<input type=hidden name=Culture value='.$this->culture.'>".
                <input type=hidden name=sEncoding value=utf-8
               "<input type=submit value="Пополнить">".
               "</form>';

        //"<input type=hidden name=IncCurrLabel value=$in_curr>".
    }

}