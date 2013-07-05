<?php

class Invitations extends CActiveRecord
{
    // лимит смс-сообщений в сутки
    const limitSMS = 2;

    //статусы смс-приглашения
    const STATUS_CREATE = 0;//создано(записали в базу)
    const STATUS_SEND = 1;// отправлено
    const STATUS_SHIP = 2;// доставлено
    const STATUS_ERROR_SHIP = 3;//ошибка при доставке

    /*
     * получаем статус отправленного приглашения
     */
    public function getStatusSend(){
        if($this->status==self::STATUS_SEND){
            return 'Отправлено';
        }
        if($this->status==self::STATUS_CREATE){
            return 'Создано';
        }
        if($this->status==self::STATUS_ERROR_SHIP){
            return 'Ошибка, при доставке';
        }
        if($this->status==self::STATUS_SHIP){
            return 'Доставлено';
        }
    }

    // список телефонных номеров, на которые будем отправлять смс
    public $phoneList;

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Invitations the static model class
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
		return '{{invitations}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('phoneList,invitations_text', 'required'),
			array('create_at, status', 'numerical', 'integerOnly'=>true),
			array('invitations_text', 'length', 'max'=>256),
			array('partner_id', 'length', 'max'=>11),

            array('phone', 'length', 'max'=>20),
            array('service_id', 'length', 'max'=>32),
            //array('phone', 'DPhone'),

            array('invitations_text','checkSmsLenght'),

            //array('phoneList', 'filter','filter'=>array('MainFilter', 'mobilePhone')),
            array('phoneList', 'validPhoneList'),
            array('phoneList', 'checkLimitSms'),
//            array('phone1, phone2','DPhone'),

            array('create_at', 'default', 'value'=>time()),
            array('partner_id', 'default', 'value'=>Yii::app()->user->id),


			array('id, create_at, invitations_text, status, partner_id, phone', 'safe', 'on'=>'search'),
		);
	}

    public function validPhoneList($attribute,$params=array())
    {
        if(!$this->hasErrors())
        {
            $list_p = explode(PHP_EOL, $this->phoneList);

            foreach($list_p as $phone){
                if(!$this->hasErrors()){
                    $validator=CValidator::createValidator('DPhone',$this,$attribute,$params);
                    $validator->validate($this,array($attribute));
                }
            }

        }
    }

    /*
     * проверяем длину смс, чтобы весь текст приглашения вместился в одно сообщение
     */
    public function checkSmsLenght(){
        if(!$this->hasErrors()){
            //текст смс
            $text = $this->invitations_text;

            // шаблон
            $pattern = '/[а-яА-Я]+/';

            //проверка на кириллицу
            preg_match($pattern, $text, $matches);

            //константы длины
            $rusLimits = array(70, 67);
            $engLimits = array(160, 153);

            //длина строки
            $len=strlen($text);

            //кириллица
            if ( sizeof($matches) > 0 )
            {
                if ($len<=$rusLimits[0])
                {
                    $smscount=1;
                }
                else
                {
                    $smscount=ceil($len/$rusLimits[1]);
                }
            }
            else
                //латиница
            {
                if ($len<=$engLimits[0])
                {
                    $smscount=1;
                }
                else
                {
                    $smscount=ceil($len/$engLimits[1]);
                }

            }

            if($smscount>1){
                $this->addError('invitations_text', 'Текст сообщения превышает длину, допустимо для кириллицы-70 символов, для латиницы-134 символов');
            }
        }
    }

    /*
     * проверяем лимит смс сообщений в сутки
     * по умолчанию - 50
     */
    public function checkLimitSms(){

        $current_count = 0;

        if(!$this->hasErrors()){

            //дата начала суток
            $dateStart = mktime(0, 0, 0, date("m")  , date("d"), date("Y"));
            // дата завершения суток
            $dateEnd = mktime(23, 59, 59, date("m")  , date("d"), date("Y"));

            $sql = 'SELECT COUNT(id) AS count FROM {{invitations}} WHERE partner_id=:partner_id AND :start_date<create_at AND :end_date>create_at';
            $connect = Yii::app()->db;
            $query = $connect->createCommand($sql);
            $query->bindValue(':partner_id', Yii::app()->user->id, PDO::PARAM_INT);
            $query->bindValue(':end_date', $dateEnd);
            $query->bindValue(':start_date', $dateStart);

            $result = $query->queryRow();

            if($result['count']==''){
                $current_count = 0;
            }else{
                $current_count = $result['count'];
            }

            if(intval($result['count'])>self::limitSMS){
                $this->addError('invitations_text','Лимит отправленных смс в сутки вами исчерпан');
            }
        }

        if(!$this->hasErrors() && !empty($this->phoneList)){

            $list_p = explode(PHP_EOL, $this->phoneList);

            if($current_count+sizeof($list_p)>self::limitSMS){
                $this->addError('phoneList','Кол-во получателей, превышает допустимое кол-во');
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
			'create_at' => 'Дата',
			'invitations_text' => 'Текст приглашения',
			'status' => 'Статус',
			'partner_id' => 'Автор',
            'phoneList'=>'Список получателей',
            'phone'=>'Телефон',
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
		$criteria->compare('create_at',$this->create_at);
		$criteria->compare('invitations_text',$this->invitations_text,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('partner_id',$this->partner_id,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}