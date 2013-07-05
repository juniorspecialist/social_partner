<?php

/**
 * This is the model class for table "{{partnership_set}}".
 *
 * The followings are the available columns in table '{{partnership_set}}':
 * @property integer $id
 * @property string $title
 * @property string $desc
 * @property string $price
 * @property string $img
 *
 * The followings are the available model relations:
 * @property BuyingPartnershipSet[] $buyingPartnershipSets
 */
class PartnershipSet extends CActiveRecord
{

    public $pathPhoto = 'upload';
    public $ajaxLink;

    /*
     * возвращаем полный путь к фото
     */
    public function getPhotoPath(){
        return Yii::app()->request->baseUrl . '/' . $this->pathPhoto . '/' . $this->img;
    }

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return PartnershipSet the static model class
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
		return '{{partnership_set}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title, desc, price, img', 'required'),
			array('title, img', 'length', 'max'=>255),
			array('price', 'length', 'max'=>10),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, title, desc, price, img', 'safe', 'on'=>'search'),
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
			'buyingPartnershipSets' => array(self::HAS_MANY, 'BuyingPartnershipSet', 'partnership_set_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'title' => 'Название',
			'desc' => 'Описание',
			'price' => 'Цена',
			'img' => 'Фото',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('desc',$this->desc,true);
		$criteria->compare('price',$this->price,true);
		$criteria->compare('img',$this->img,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

    /*
     * формируем аякс-ссылку на покупку партнёрского комплекта
     */
    public function getAjaxLink($title='Купить', $url_action='', $post_data=''){
        return
            CHtml::ajaxLink(
                $title,
                $url_action,
                array(
                    'type' => 'POST',
                    'datatype'=>'json',
                    // можно спросить до отправки что-то или проверить данные какие-нибудь.
                    'beforeSend' => "function(request){
                    }",
                    'success' => "function(data){
                        if(data.result=='ok'){
                            $.pnotify({'type':'success','title':'Login Successful!','text':'You successfully logged in. Enjoy!'});
                        }else{
                            $.pnotify({'type':'success','title':'Login Successful!','text':'You successfully logged in. Enjoy!'});
                        }
                    }",
                    'data' => $post_data, // посылаем значения
                    'cache'=>'false' // если нужно можно закешировать
                ),
                array( // самое интересное
                    'href' => 'javascript: void(0)',// подменяет ссылку на левую
                    //'class' => "sadfsadfsadclass" // добавляем какой-нить класс для оформления
                    'id'=>uniqid(),
                    'style'=>'margin-left:40px;'
                )
            );
    }
}