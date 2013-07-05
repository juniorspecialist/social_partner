<?php
class HistoryAccount extends CActiveRecord
{
    // типы операций
    const TYPE_PRIHOD = 1;
    const TYPE_RASHOD = 2;

    //назначения платежей
    const DESTIONATION_PROFIT = 1;// доход
    const DESTIONATION_BUY_PARTNER = 2;// покупка партнёрского комплекта
    const DESTIONATION_ADD_BALANCE = 4;// пополнение баланса
    const DESTIONATION_TRANSFER = 3;// перевод средств
    const DESTIONATION_CASHOUT = 5;// вывод средств
    const DESTIONATION_PROFIT_SYSTEM = 6;// прибыль системы, после вычита всех партнёрских отчислений
    const DESTIONATION_REG_DEPOSIT = 7;// рег. взнос


	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return HistoryAccount the static model class
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
		return '{{history_account}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('type_operation, partner_id, bonuse, destination, bonus_sender', 'required'),
			array('type_operation, destination', 'numerical', 'integerOnly'=>true),
			array('partner_id, bonuse, create_at', 'length', 'max'=>10),
			array('bonus_sender', 'length', 'max'=>11),
            array('create_at','default', 'value'=>time()),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, type_operation, partner_id, bonuse, destination, create_at, bonus_sender', 'safe', 'on'=>'search'),
		);
	}

    /*
     * назначение платежа
     */
    public function getDestinationPay(){
        if($this->destination==self::DESTIONATION_ADD_BALANCE){
            return 'Пополнение баланса';
        }
        if($this->destination==self::DESTIONATION_PROFIT){
            return 'Доход';
        }
        if($this->destination==self::DESTIONATION_BUY_PARTNER){
            return 'Покупка партнерского комплекта';
        }
        if($this->destination==self::DESTIONATION_TRANSFER){
            return 'Перевод';
        }
        if($this->destination==self::DESTIONATION_CASHOUT){
            return 'Вывод средств';
        }
        if($this->destination==self::DESTIONATION_PROFIT_SYSTEM){
            return 'Прибыль системы';
        }
        if($this->destination==self::DESTIONATION_REG_DEPOSIT){
            return 'Регистрационный взнос';
        }
    }

    /*
     * тип операции
     */
    public function getTypeOperation(){
        if($this->type_operation==self::TYPE_PRIHOD){
            return 'Приход';
        }else{
            return 'Расход';
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
			'partner' => array(self::BELONGS_TO, 'Partner', 'partner_id'),
			'bonusSender' => array(self::BELONGS_TO, 'Partner', 'bonus_sender'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'type_operation' => 'Тип операции',
			'partner_id' => 'Получатель',
			'bonuse' => 'Бонус',
			'destination' => 'Назначение',
			'create_at' => 'Дата',
			'bonus_sender' => 'Отправитель',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('type_operation',$this->type_operation);
		$criteria->compare('partner_id',$this->partner_id,true);
		$criteria->compare('bonuse',$this->bonuse,true);
		$criteria->compare('destination',$this->destination);
		$criteria->compare('create_at',$this->create_at,true);
		$criteria->compare('bonus_sender',$this->bonus_sender,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}