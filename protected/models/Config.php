<?php
/**
 * Created by JetBrains PhpStorm.
 * User: artem
 * Date: 24.05.13
 * Time: 15:02
 * To change this template use File | Settings | File Templates.
 */

/**
 * This is the model class for table "{{config}}".
 *
 * The followings are the available columns in table '{{config}}':
 * @property string $id
 * @property string $param
 * @property string $value
 * @property string $default
 * @property string $label
 * @property string $type
 */
class Config extends CActiveRecord
{
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return '{{config}}';
    }

    public function rules()
    {
        return array(
            array('value', 'safe'),
            array('id, param, value, label, type, default', 'safe', 'on'=>'search'),
        );
    }
}