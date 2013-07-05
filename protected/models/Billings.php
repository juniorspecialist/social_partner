<?php
class Billings extends CActiveRecord
{

    // статусы заявки на пополнение
    const STATUS_SEND = 1;// счет выставлен, пользователь не оплатил
    const STATUS_PAID = 2;// партнёр оплатил счет

    // тип системы через которую производим пополнение баланса
    const TYPE_ROBOKASSA = 1;//платёжная система - робокасса
    //const TYPE_ROBOKASSA = 1;//платёжная система - робокасса


	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Billings the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{billings}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('amount, type_money_system', 'required'),
			array('partner_id, amount, create_at, status, type_money_system', 'numerical', 'integerOnly'=>true),

            array('create_at', 'default', 'value'=>time()),
            array('partner_id', 'default', 'value'=>Yii::app()->user->id),
            array('status', 'default', 'value'=>self::STATUS_SEND),

			array('id, partner_id, amount, create_at, status, type_money_system', 'safe', 'on'=>'search'),
		);
	}

    public function getTypeMoneySystem($type){
        if($type==self::TYPE_ROBOKASSA){
            return 'Робокасса';
        }
    }

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'partner_id' => 'Партнер',
			'amount' => 'Сумма пополнения (рублей)',
			'create_at' => 'Дата',
			'status' => 'Статус',
			'type_money_system' => 'Тип системы пополнения баланса',
		);
	}
}
