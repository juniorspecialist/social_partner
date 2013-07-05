<?php
/**
 * Created by JetBrains PhpStorm.
 * User: artem
 * Date: 15.05.13
 * Time: 9:31
 * To change this template use File | Settings | File Templates.
 */

class BuyPartnerAction extends CAction {
    /**
     * @var string name of the CActiveRecord class.
     */
    public $modelName;
    /**
     * @var string name of the search result view.
     */
    public $view;
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

        // формируем цену партнёрского кмоплекта
        if($model->status==Partner::STATUS_MEMBER){
            $price = 4000;//3600 - цена комплекта+ 400 рег. взнос
        }else{
            $price = 3600;
        }

        //проверяем достаточно ли средств для покупки
        if($model->balance>=$price){

            // денег хватает - совершаем покупку комплекта
            $this->buy($model);

            echo 'ok';
        }else{

            // не достаточно средств
            Yii::app()->user->setFlash('error','Ошибка! На вашем счете не хватает '.($price-$model->balance).' баллов для покупки комплекта');
            echo 'error';
        }

//        $this->getController()->renderPartial('_personal_info', array(
//            'model'=>$model,
//            'parent'=>$model->parent()->find(),
//        ), false, true);


        Yii::app()->end();
    }
    /**
     * @return CActiveRecord
     */
    protected function getModel()
    {
        return CActiveRecord::model($this->modelName);
    }

    /*
     * покупка партнёрского аккаунта
     */
    public function buy($partner){

        $buy = new BuyPartnerComplekt();

        $buy->who_buys = Yii::app()->user->id;
        $buy->for_whom = Yii::app()->user->id;
        $buy->_partnerWhoBuy = $partner;
        $buy->_partner_ship_id = $_POST['id'];
        $buy->run();
        if($buy->error){
            //echo $buy->error_text;
            Yii::app()->user->setFlash('error',$buy->error_text);
            return false;
        }else{
            Yii::app()->user->setFlash('success','Поздравляем, покупка совершена успешно!');
            return true;
            //echo 'ok';
        }
    }

    public function loadPartner($id=''){

        if(!empty($id)){
            $model = Partner::model()->findByPk($id);
        }else{
            $model = Partner::model()->findByPk(Yii::app()->user->id);
        }

        if($model===null)
            throw new CHttpException(404,'The requested page does not exist.');
        return $model;
    }
}