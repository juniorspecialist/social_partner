<?php
/**
 * Created by JetBrains PhpStorm.
 * User: artem
 * Date: 07.05.13
 * Time: 18:27
 * To change this template use File | Settings | File Templates.
 */
class BusinessController extends BaseUserController{

    /**
     * @var string name of the model class.
     */
    public $modelName = 'Partner';

    public $parent_id = 'parent_id';
    public $label = 'fio';

    public  $defaultAction = 'personal';

    public function actions(){
        return array(
            /* ajax запрос для построения дерева рефералов текущего юзера
             * при первом запуске $showRoot=true, потому что нужно отобразить родителя(текущего юзера во главе дерева)
             * при последующих запросах родителя не отображаем*/
            'tree'=>array(
                'class'=>'ext.actions.TreeAction',
                'modelName'=>'Partner',
            ),
            /*
             * развитие бизнесса/ данные об общих количествах сотрудников разных уровней и статусов
             * + ссылки на детализацию данных
             */
            'progress'=>array(
                'class'=>'ext.actions.ProgressAction',
                'modelName'=>'Partner',
                'view'=>'progress',
            ),
            'buyPartner'=>array(
                'class'=>'ext.actions.BuyPartnerAction',
                'modelName'=>'Partner',
            ),

            /*таблицы данных во вкладке - развитие бизнеса */
            'ajaxtbl'=>array(
                'class'=>'ext.actions.AjaxTblAction',
                'modelName'=>'Partner',
            ),
            // подробная информация о выбранном реферале в дереве
            'ajaxinfo'=>array(
                'class'=>'ext.actions.AjaxInfoAction',
                'modelName'=>'Partner',
                'view'=>'progress',
            )
        );
    }

//    public function actionIndex()
//    {
//        // отображаем вкладки с данные по пользователю
//        $this->render('index');
//    }

    public function actionStructure(){

        $this->setPageTitle(Yii::app()->config->getPageTitle('Структура пользователя'));

        // фильтруем данные по дереву, отображаем не дерево, а таблицу
        if(Yii::app()->request->isAjaxRequest){

            $model = new Partner('search');

            //$model->unsetAttributes();  // clear any default values

            if(isset($_GET['Partner'])){
                $model->attributes=$_GET['Partner'];
            }

            $partner = $this->loadPartner();

            $criteria=new CDbCriteria;
            $criteria->condition = "lft>".$partner->lft." AND  rgt<".$partner->rgt."";
            $criteria->compare('id',$model->id);
            $criteria->compare('fio',$model->fio, true);
            $criteria->compare('email',$model->email,true);
            $criteria->compare('status',$model->status);
            $criteria->compare('partner_level',$model->partner_level);

            //$descendants = $partner->descendants()->findAll();

            $dataProvider = new CActiveDataProvider('Partner', array(
                'criteria'=>$criteria,
                'pagination'=>array(
                    'pageSize'=>100,
                ),
            ));

            $this->renderPartial('_gridview_with_filters',array(
                'model'=>$model,
                'dataProvider'=>$dataProvider,
            ), false, true);

            Yii::app()->end();
        }

        $this->render('structure', array('model'=>new Partner()));
    }

    /*
     * личное развитие бизнесса
     */
    public function actionPersonal(){

        $this->setPageTitle(Yii::app()->config->getPageTitle('Личное развитие бизнеса'));

        if(!Yii::app()->request->isAjaxRequest){
            //throw new CHttpException(400,'Invalid request');
        }

        $dataProviderPartnershipSet = new CActiveDataProvider('PartnershipSet');

        $model = $this->loadPartner();

        $this->render('personal',array(
            'model'=>$model,
            'parent'=>$model->parent()->find(),
            'dataProviderPartnerComplekts'=>$dataProviderPartnershipSet,
        ));
    }


    /*
     * доход пользователя от партнёрской программы+потребительской программы
     */
    public function actionProfit(){

        $this->setPageTitle(Yii::app()->config->getPageTitle('Доход по партнерской программе'));

        $model = new Profit();

        $this->render('profit', array(
            'model'=>$model,
        ));
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