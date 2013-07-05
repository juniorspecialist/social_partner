<?php

/**
 * This is the model class for table "{{pm}}".
 *
 * The followings are the available columns in table '{{pm}}':
 * @property string $id
 * @property string $content
 * @property string $partner_sender
 * @property string $partner_destination
 * @property integer $status
 *
 * The followings are the available model relations:
 * @property Partner $partnerSender
 * @property Partner $partnerDestination
 */
class Pm extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Pm the static model class
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
		return '{{pm}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('content, partner_sender, partner_destination, status', 'required'),
			array('status', 'numerical', 'integerOnly'=>true),
			array('partner_sender, partner_destination', 'length', 'max'=>10),
            array('create_at','default', 'value'=>time()),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, content, partner_sender, partner_destination, status, create_at', 'safe', 'on'=>'search'),
		);
	}

    public function scopes()
    {
        return array(
            'outbox'=>array(
                'condition'=>'partner_sender='.Yii::app()->user->id,
                'order'=>'create_time DESC',
            ),
            'inbox'=>array(
                'condition'=>'partner_destination='.Yii::app()->user->id,
                'order'=>'create_time DESC, status ASC',
                //'limit'=>5,
            ),
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
			'partnerSender' => array(self::BELONGS_TO, 'Partner', 'partner_sender'),
			'partnerDestination' => array(self::BELONGS_TO, 'Partner', 'partner_destination'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'content' => 'Content',
			'partner_sender' => 'Partner Sender',
			'partner_destination' => 'Partner Destination',
			'status' => 'Status',
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
		$criteria->compare('content',$this->content,true);
		$criteria->compare('partner_sender',$this->partner_sender,true);
		$criteria->compare('partner_destination',$this->partner_destination,true);
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}