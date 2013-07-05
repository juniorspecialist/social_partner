<?php

/**
 * This is the model class for table "{{buying_partnership_set}}".
 *
 * The followings are the available columns in table '{{buying_partnership_set}}':
 * @property string $id
 * @property string $partner_id
 * @property string $create_at
 * @property integer $partnership_set_id
 * @property integer $type_buying
 * @property string $who_buys
 *
 * The followings are the available model relations:
 * @property Partner $partner
 * @property PartnershipSet $partnershipSet
 * @property Partner $whoBuys
 */
class BuyingPartnershipSet extends CActiveRecord
{

    const TYPE_NONAME = 0;//не именной партнёрский комплект
    const TYPE_NAME = 1;// именной тип партнёрского комплекта

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return BuyingPartnershipSet the static model class
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
		return '{{buying_partnership_set}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('partner_id, partnership_set_id, type_buying, who_buys', 'required'),
			array('partnership_set_id, type_buying, partner_id, who_buys', 'numerical', 'integerOnly'=>true),
			array('partner_id, who_buys', 'length', 'max'=>11),
            // покупка текущим времени фиксируем
            array('create_at', 'default', 'value'=>time()),
            // покупает комплект всегда текущий юзер
            array('who_buys', 'default', 'value'=>Yii::app()->user->id),
            array('type_buying', 'in','range'=>range(self::TYPE_NONAME,self::TYPE_NAME)),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, partner_id, create_at, partnership_set_id, type_buying, who_buys', 'safe', 'on'=>'search'),
		);
	}

    /**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
			'partner' => array(self::BELONGS_TO, 'Partner', 'partner_id'),
			'partnershipSet' => array(self::BELONGS_TO, 'PartnershipSet', 'partnership_set_id'),
			'whoBuys' => array(self::BELONGS_TO, 'Partner', 'who_buys'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'partner_id' => 'Для кого покупается партнёрский комплект',
			'create_at' => 'Дата/время',
			'partnership_set_id' => 'Партнерский комплект',
			'type_buying' => 'Тип комплекта',
			'who_buys' => 'Кто покупает',
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
		$criteria->compare('partner_id',$this->partner_id,true);
		$criteria->compare('create_at',$this->create_at,true);
		$criteria->compare('partnership_set_id',$this->partnership_set_id);
		$criteria->compare('type_buying',$this->type_buying);
		$criteria->compare('who_buys',$this->who_buys,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

    /*
     * кол-во проданных партнёрских комплектов за весь период времени
     */
    public static function numberSales(){

        $sql = 'SELECT COUNT(id) AS count FROM {{buying_partnership_set}}';

        $result = Yii::app()->db->createCommand($sql)->queryRow();

        return $result['count'];
    }
}