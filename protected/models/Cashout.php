<?php
class Cashout extends CActiveRecord
{

    /// способы выплаты
    const TYPE_YANDEX_MONEY = 1;// яндекс деньги
    const TYPE_WEBMONEY = 2;// вебмани
    const TYPE_CARD = 3;// пластиковая карта
    const TYPE_CASH_IN_OFFICE = 4;// Наличными в офисе компании


    // статусы заявки на вывод денежных средств
    const STATUS_SEND = 0;// заявка отправлена пользователем
    const STATUS_ACCEPT = 1;// заявка выполнена админом

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Cashout the static model class
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
		return '{{cashout}}';
	}

    public function getTypeCash($type = ''){

        if(!empty($type)){
            $type_cash = $type;
        }else{
            $type_cash = $this->type_cash;
        }

        if($type_cash==self::TYPE_CARD){
            return 'Пластиковая карта';
        }
        if($type_cash==self::TYPE_WEBMONEY){
            return 'WebMoney';
        }
        if($type_cash==self::TYPE_YANDEX_MONEY){
            return 'Яндекс Деньги';
        }
        if($type_cash==self::TYPE_CASH_IN_OFFICE){
            return 'Наличными в офисе компании';
        }
    }

    public function getStatusCashOut(){
        if($this->status==self::STATUS_SEND){
            return 'Отправлена пользователем';
        }
        if($this->status==self::STATUS_ACCEPT){
            return 'Выполнена администратором';
        }
    }

    /*
     * получаем список возможных типов выплат
     */
    public function getTypeList(){
        return array(
//            self::TYPE_CARD=>$this->getTypeCash(self::TYPE_CARD),
//            self::TYPE_WEBMONEY=>$this->getTypeCash(self::TYPE_WEBMONEY),
//            self::TYPE_YANDEX_MONEY=>$this->getTypeCash(self::TYPE_YANDEX_MONEY),
            self::TYPE_CASH_IN_OFFICE=>$this->getTypeCash(self::TYPE_CASH_IN_OFFICE),
        );
    }

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('type_cash,  sum_cash, desc', 'required'),
			array('type_cash, sum_cash', 'numerical', 'integerOnly'=>true),
			array('sum_cash, status', 'length', 'max'=>10),
			//array('partner_id', 'length', 'max'=>11),
            array('partner_id', 'default', 'value'=>Yii::app()->user->id),
            array('status', 'default', 'value'=>self::STATUS_SEND),
            // сумма в заявке не должна быть больше текущего баланса юзера
            array('sum_cash','checkSumMoneyOut'),
            array('create_at', 'default', 'value'=>time()),
			array('id, type_cash, create_at, sum_cash, status, partner_id, desc', 'safe', 'on'=>'search'),
		);
	}

    /*
     * проверяем сумму вывода денег, чтобы она не превышала остаток на балансе юзера
     */
    public function checkSumMoneyOut(){
        if(!$this->hasErrors()){

            $balance = Partner::getBalance(Yii::app()->user->id);

            // если сумма вывода больше чем баланс - ошибка
            if($this->sum_cash>$balance){
                $this->addError('sum_cash', 'Сумма не может превышать текущий ваш баланс - '.$balance);
            }
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'type_cash' => 'Способ выплаты',
			'create_at' => 'Дата',
			'sum_cash' => 'Сумма',
			'status' => 'Статус',
			'partner_id' => 'Партнер',
			'desc' => 'Описание',
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
		$criteria->compare('type_cash',$this->type_cash);
		$criteria->compare('create_at',$this->create_at,true);
		$criteria->compare('sum_cash',$this->sum_cash,true);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('partner_id',$this->partner_id,true);
		$criteria->compare('desc',$this->desc,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}