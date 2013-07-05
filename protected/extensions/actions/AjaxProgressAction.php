<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Саня
 * Date: 12.05.13
 * Time: 18:01
 * To change this template use File | Settings | File Templates.
 */

class AjaxProgressAction extends CAction  {
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
        $data = $this->getModel()->findByPk(Yii::app()->user->id);

        $this->getController()->renderPartial('progress',array(
            'dataProvider'=>$data,
            'childCountMember'=>$data->countChildren(Partner::STATUS_MEMBER),
            'childCountPartner'=>$data->countChildren(Partner::STATUS_Partner),
            'childCountPartnerLevel1'=>$data->countChildren(Partner::STATUS_Partner, 1),
            //'arrow'=>$arrow,
        ),false, true);
   	}
    /**
   	 * @return CActiveRecord
   	 */
   	protected function getModel()
   	{
   		return CActiveRecord::model($this->modelName);
   	}
}