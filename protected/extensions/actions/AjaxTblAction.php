<?php
/**
 * Created by JetBrains PhpStorm.
 * User: artem
 * Date: 28.05.13
 * Time: 10:53
 * To change this template use File | Settings | File Templates.
 */

class AjaxTblAction extends CAction  {
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
        if(!Yii::app()->request->isAjaxRequest){ throw new CHttpException(400,'Invalid request'); }

        // относительно текущего юзера производим выборку его дочерних рефералов по указанному типу(статусу)
        $model = $this->loadPartner();
        $model->setScenario('search');
        //$model->unsetAttributes();
        if(isset($_GET['Partner'])){
            $model->attributes = $_GET['Partner'];
        }

        $criteria=new CDbCriteria;
        $criteria->condition = 'lft>'.$model->lft.' AND rgt<'.$model->rgt.' AND id!='.Yii::app()->user->id;

        // со статусом - участник
        if($model->type==Partner::STATUS_MEMBER){
            $criteria->addColumnCondition(array('status'=>Partner::STATUS_MEMBER));
        }
        // со статусом - ПАРТНЁР
        if($model->type==Partner::STATUS_Partner){
            $criteria->addColumnCondition(array('status'=>Partner::STATUS_Partner));
        }
        // выводим «Партнеров» на 1 уровне
        if($model->type==-1){
            //$criteria->condition = 'status='.Partner::STATUS_Partner.' AND level<='.($model->level+1).'';
            $criteria->addCondition('status='.Partner::STATUS_Partner.' AND level<='.($model->level+1).'');
        }

        $dataProvider = new CActiveDataProvider('Partner', array(
            'criteria'=>$criteria,
            'pagination'=>array(
                'pageSize'=>100,
            ),
        ));

        $this->getController()->renderPartial('ajax', array(
                'dataProvider'=>$dataProvider,
                'model'=>$model,
            )
        );
    }
    /**
     * @return CActiveRecord
     */
    protected function getModel()
    {
        return CActiveRecord::model($this->modelName);
    }


    public function loadPartner($id=''){

        if(!empty($id)){
            $model = $this->getModel()->findByPk($id);
        }else{
            $model = $this->getModel()->findByPk(Yii::app()->user->id);
        }

        if($model===null)
            throw new CHttpException(404,'The requested page does not exist1.');
        return $model;
    }
}