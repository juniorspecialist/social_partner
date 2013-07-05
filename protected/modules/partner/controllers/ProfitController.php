<?php
/**
 * Created by JetBrains PhpStorm.
 * User: artem
 * Date: 13.05.13
 * Time: 17:03
 * To change this template use File | Settings | File Templates.
 */

class ProfitController extends BaseUserController {

    public function actions(){
        return
            array(
                'buyPartner'=>array(
                    'class'=>'ext.actions.BuyPartnerAction',
                    'modelName'=>'Partner',
                ),
            );
    }

    /*
     *доходы по партнерской программе
     */
    public function actionPartner(){

        $this->setPageTitle(Yii::app()->config->getPageTitle('Доходы по партнерской программе'));

        $model = new Profit();
        $model->setScenario('search');
        if(isset($_GET['Profit'])){
            $model->attributes = $_GET['Profit'];
        }
        $criteria = new CDbCriteria;
        $criteria->condition = 'destination_account='.Yii::app()->user->id;
        if($model->dateFrom){
            $criteria->condition = 'create_at>'.strtotime($model->dateFrom);
        }
        if($model->dateTo){
            $criteria->condition = 'create_at<'.strtotime($model->dateTo);
        }
//        $dataProvider = new CActiveDataProvider('Profit', array(
//            'criteria'=>$criteria,
//            'sort'=>array(
//                'defaultOrder'=>'t.id DESC',
//            ),
//            'pagination'=>array(
//                'pageSize'=>100,
//            ),
//        ));


        /*
        if(Yii::app()->request->isAjaxRequest){

            $this->renderPartial('_table_profit_partner', array(
                'dataProvider'=>$dataProvider,
                'model'=>$model
            ),false, true);

            Yii::app()->end();
        }*/

        $this->render('partner', array(
            'model'=>$model,
            //'dataProvider'=>$dataProvider,
            'partner'=>$this->loadPartner(),
        ));
    }

    /*
     * доходы с партнёрской программы по Аякусу
     */
    public function actionAjaxProfitTable(){

        if(!Yii::app()->request->isAjaxRequest){ throw new CHttpException(400,'Invalid request'); }

        $model = new Profit();

        $model->setScenario('search');
        if(isset($_GET['Profit'])){
            $model->attributes = $_GET['Profit'];
        }
        $criteria = new CDbCriteria;
        $criteria->condition = 'destination_account='.Yii::app()->user->id;
        if($model->dateFrom){
            $criteria->condition = 'create_at>'.strtotime($model->dateFrom);
        }
        if($model->dateTo){
            $criteria->condition = 'create_at<'.strtotime($model->dateTo);
        }
        $dataProvider = new CActiveDataProvider('Profit', array(
            'criteria'=>$criteria,
            'sort'=>array(
                'defaultOrder'=>'t.id DESC',
            ),
            'pagination'=>array(
                'pageSize'=>100,
            ),
        ));
        $this->renderPartial('_table_profit_partner', array(
            'dataProvider'=>$dataProvider,
            'model'=>$model
        ),false, true);

        Yii::app()->end();
    }


    /*
     * информация о партнёре, по текущего юзеру
     */
    public function loadPartner(){

        $model = Partner::model()->findByPk(Yii::app()->user->id);

        if($model===null)
            throw new CHttpException(404,'The requested page does not exist.');
        return $model;
    }
}