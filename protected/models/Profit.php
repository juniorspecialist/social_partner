<?php
class Profit extends CActiveRecord
{

    public $dateFrom;
    public $dateTo;

    public $startMonth;//дата начала месяца(текущего)
    public $endMonth;//дата последнего дня месяца(текущего)

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Profit the static model class
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
		return '{{finance_partnership}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('destination_account, point, sender_account, has_partners, has_personal_partners, active_points, partner_level, active_points_sender, partner_level_sender, level_cooperator, bonus_from_level1, bonus_from_other_levels', 'required'),
			array('partner_level, partner_level_sender, bonus_from_level1, bonus_from_other_levels', 'numerical', 'integerOnly'=>true),
			array('destination_account, point, sender_account, has_partners, has_personal_partners, active_points, active_points_sender, level_cooperator, create_at', 'length', 'max'=>10),
			// The following rule is used by search().
            array('create_at', 'default', 'value'=>time()),
			// Please remove those attributes that should not be searched.
			array('id, destination_account, point, sender_account, has_partners, has_personal_partners, active_points, partner_level, active_points_sender, partner_level_sender', 'safe', 'on'=>'search'),
            array('dateFrom, dateTo, level_cooperator, create_at, bonus_from_level1, bonus_from_other_levels', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'destinationAccount' => array(self::BELONGS_TO, 'Partner', 'destination_account'),
			'senderAccount' => array(self::BELONGS_TO, 'Partner', 'sender_account'),
		);
	}

    /*
     * возвращаем дату дату первого дня текущего месяца
     */
    public function getStartMonth(){
        return date("Y-m-01");
    }

    /*
     * возращаем дату последнего дня месяца
     */
    public function getEndMonth(){
        return date("Y-m-t");
    }

    public function getPartnerLevelSender($data,$row){

        //echo '<pre>'; print_r($row); die();

        return  Partner::getPartnerLevel_($data->partner_level_sender);
    }

    public function getPartnerLevelDestination($data,$row){
        //echo '<pre>'; print_r($row); die();

        //print_r($data); die();
        return  Partner::getPartnerLevel_($data->partner_level);
    }


    /*
     * сумма баллов внесенная участниками система для оплаты партнёрских комплектов
     */
    public function inComeProfitAll(){

        $connect = Yii::app()->db;

        $select = 'SELECT SUM(point) AS profit FROM {{finance_partnership}}';

        $query = $connect->createCommand($select);

        $result = $query->queryRow();

        if($result['profit']==null){
            return 0;
        }else{
            return $result['profit'];
        }
    }


    /*
     * заработок за интервал времени по партнёрской программе
     * в разрезе текущего юзера
     * по выбранному интервалу дат
     * $sender_account - от какого юзера мы получили прибыль
     */
    public function inComeProfit($dataFrom='', $dataTo='', $sender_account=''){

        $connect = Yii::app()->db;


        $where = ' WHERE destination_account='.Yii::app()->user->id;

        if($dataFrom){
            $where.=' AND create_at>'.strtotime($dataFrom);
        }

        if($dataFrom){
            $where.=' AND create_at<'.strtotime($dataTo);
        }

        if($sender_account){
            $where.=' AND sender_account='.$sender_account;
        }

        $select = 'SELECT SUM(point) AS profit FROM {{finance_partnership}}';

        $query = $connect->createCommand($select.$where);

        $result = $query->queryRow();

        if($result['profit']==null){
            return 0;
        }else{
            return $result['profit'];
        }
    }

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
            'destinationAccount'=>'Получатель бонуса',
			'destination_account' => 'Получатель бонуса',
			'point' => 'Размер бонуса, баллы',
            'senderAccount'=>'Отправитель бонуса',
			'sender_account' => 'Отправитель бонуса',
			'has_partners' => 'Кол-во пользователей в статусе Партнер у Вас',
			'has_personal_partners' => 'Кол-во личных Партнерских комплектов у Вас',
			'active_points' => 'Кол-во баллов активности у Вас',
			'partner_level' => 'Ваш уровень в Партнерской программе',
			'active_points_sender' => 'Кол-во баллов активности у отправителя',
			'partner_level_sender' => 'Уровень отправителя в Партнерской Программе',
			'level_cooperator' => 'Уровень сотрудника как Вашего реферала',
			'create_at' => 'Дата и время',
			'bonus_from_level1' => 'Ваш бонус с 1 уровня рефералов, %',
			'bonus_from_other_levels' => 'Ваш бонус с 2-10 уровней, %',
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
		$criteria->compare('destination_account',$this->destination_account,true);
		$criteria->compare('point',$this->point,true);
		$criteria->compare('sender_account',$this->sender_account,true);
		$criteria->compare('has_partners',$this->has_partners,true);
		$criteria->compare('has_personal_partners',$this->has_personal_partners,true);
		$criteria->compare('active_points',$this->active_points,true);
		$criteria->compare('partner_level',$this->partner_level);
		$criteria->compare('active_points_sender',$this->active_points_sender,true);
		$criteria->compare('partner_level_sender',$this->partner_level_sender);
		$criteria->compare('level_cooperator',$this->level_cooperator,true);
		$criteria->compare('create_at',$this->create_at,true);
		$criteria->compare('bonus_from_level1',$this->bonus_from_level1);
		$criteria->compare('bonus_from_other_levels',$this->bonus_from_other_levels);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}