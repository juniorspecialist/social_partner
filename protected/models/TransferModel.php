<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Саня
 * Date: 29.05.13
 * Time: 21:26
 * To change this template use File | Settings | File Templates.
 */

/*
 * перевод средств между пользователя системы
 */
class TransferModel extends CFormModel {

    public $from_partner;
    public $to_partner;
    public $sum_transfer;
    public $smsCode;

    public function rules()
   	{
   		return array(
            array('sum_transfer, to_partner', 'required', 'on'=>'transfer'),
            array('to_partner', 'numerical', 'integerOnly'=>true, 'on'=>'transfer'),
            array('sum_transfer', 'numerical', 'integerOnly'=>true, 'on'=>'transfer', 'message'=>'Сумма перевода должна быть целым числом'),

            array('from_partner', 'default','value'=>Yii::app()->user->id, 'on'=>'transfer'),
            array('to_partner', 'validToPartner', 'on'=>'transfer'),
            array('to_partner', 'issetPartner', 'on'=>'transfer'),
            array('sum_transfer', 'checkBalance', 'on'=>'transfer'),

            array('smsCode','validateSms', 'on'=>'sms'),
            array('sum_transfer, to_partner, smsCode', 'required', 'on'=>'sms'),
            array('sum_transfer, to_partner', 'numerical', 'integerOnly'=>true, 'on'=>'sms'),
            array('from_partner', 'default','value'=>Yii::app()->user->id, 'on'=>'sms'),
            array('to_partner', 'issetPartner', 'on'=>'sms'),
            array('sum_transfer', 'checkBalance', 'on'=>'sms'),
   		);
   	}

    /*
     * проверяем, чтобы получатель не был равен отправителю
     * ID получателя не совпадал с моим собственным.
     */
    public function validToPartner(){
        if(!$this->hasErrors()){
            if($this->to_partner==Yii::app()->user->id){
                $this->addError('to_partner','Вы не можете самому себе перевести баллы');
            }
        }
    }

    public function validateSms(){
        $model = new Partner();
        if($model->isValidateSmsCode($this->smsCode)){
            return true;
        }else{
            $this->addError('smsCode', 'Код смс указан не верно');
            return false;
        }
    }

    /*
     * проверяем, существует ли партнёр по указанному ID в системе
     */
    public function issetPartner(){
        if(!$this->hasErrors()){
            $connection=Yii::app()->db; // так можно сделать, если в конфигурации описан компонент соединения "db"
            $command=$connection->createCommand('SELECT id FROM {{partner}} WHERE id=:id');
            $command->bindParam(':id', $this->to_partner, PDO::PARAM_INT );
            $find = $command->queryRow();

            if(empty($find)){
                $this->addError('to_partner', 'Указанный партнёр не найден в системе');
            }
        }
    }

    /*
     * проверим баланс текущего юзера на наличие нужной суммы для пеервода
     */
    public function checkBalance(){
        if(!$this->hasErrors()){
            $balance = Partner::getBalance(Yii::app()->user->id);
            if($balance<$this->sum_transfer){
                $this->addError('sum_transfer', 'Сумма перевода больше чем ваш баланс');
            }
        }
    }

    public function attributeLabels()
   	{
   		return array(
   			'sum_transfer' => 'Сумма перевода',
   			'to_partner' => 'Номер счета получателя перевода',
   			'from_partner' => 'ID отправителя бонуса',
            'smsCode'=>'код смс',
   		);
   	}
}