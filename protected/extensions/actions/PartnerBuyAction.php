<?php
/**
 * Created by JetBrains PhpStorm.
 * User: artem
 * Date: 14.05.13
 * Time: 17:19
 * To change this template use File | Settings | File Templates.
 */
/*
 *  покупка партнерского комплекта пользователем
 */
class PartnerBuyAction extends CAction{
    /**
     * @var string name of the CActiveRecord class.
     */
    public $modelName = 'Partner';

    /**
     * Runs the action.
     */
    public function run()
    {
        if(!Yii::app()->request->isAjaxRequest){
            throw new CHttpException(400,'Invalid request');
        }

        //формируем массив с данными для отображения в форме для юзера
        $model = $this->getModel()->findByPk(Yii::app()->user->id);

        //если пользователь покупает ПЕРВЫЙ комплект, то цена 4000руб. = 3600+400(рег. взнос)
    }
    /**
     * @return CActiveRecord
     */
    protected function getModel()
    {
        return CActiveRecord::model($this->modelName);
    }
}