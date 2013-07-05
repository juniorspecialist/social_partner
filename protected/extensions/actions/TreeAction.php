<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Саня
 * Date: 12.05.13
 * Time: 16:56
 * To change this template use File | Settings | File Templates.
 */

/*
 * построение дерева по ajax-запросу как для админки, так и для личного профиля юзера
 */
class TreeAction extends CAction {

    /**
   	 * @var string name of the CActiveRecord class.
   	 */
   	public $modelName;

    public function run(){

        if(!Yii::app()->request->isAjaxRequest){
            throw new CHttpException(400,'Invalid request');
        }

        // если первый раз запускаем построение первого списка рядом - уровень(1)
        if(!isset($_GET['root'])||$_GET['root']=='source'){
            $rootId=Yii::app()->user->id;
            $showRoot=true;
        }else{// построение выбранной ветви дерева
            $rootId=$_GET['root'];
            $showRoot=false;
        }

        //$partners = Partner::model()->treedata($rootId, $showRoot);
        $partners = $this->getModel()->treedata($rootId, $showRoot);

        echo CTreeView::saveDataAsJson($partners);
    }

    /**
   	 * @return CActiveRecord
   	 */
   	protected function getModel()
   	{
   		return CActiveRecord::model($this->modelName);
   	}
}