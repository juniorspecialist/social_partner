<?php
/**
 * Created by JetBrains PhpStorm.
 * User: artem
 * Date: 07.05.13
 * Time: 18:27
 * To change this template use File | Settings | File Templates.
 */
class BusinessController extends BaseAdminController{

    /**
     * @var string name of the model class.
     */
    public $modelName = 'Partner';

    public $parent_id = 'parent_id';
    public $label = 'fio';

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

            /*покупка партнёрского комплекта*/
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
            // подробная информация о выбранном типе рефералов, указанного уровня или статуса
            /*'ajax_progress'=>array(
                'class'=>'ext.actions.AjaxProgressAction',
                'modelName'=>'Partner',
                'view'=>'progress',
            )*/
        );
    }

    public function actionIndex()
    {
        // отображаем вкладки с данные по пользователю
        $this->render('index');
    }

    public function actionStructure(){

        $this->render('structure');
    }

    /*
     * личное развитие бизнесса
     */
    public function actionPersonal(){

        if(!Yii::app()->request->isAjaxRequest){
            //throw new CHttpException(400,'Invalid request');
        }

        $dataProviderPartnershipSet = new CActiveDataProvider('PartnershipSet');

        $model = $this->loadPartner();


        $this->render('personal',array(
            'model'=>$model,
            'parent'=>$model->parent()->find(),
            'dataProviderPartnerComplekts'=>$dataProviderPartnershipSet
        ));
    }


    /*
     * смена спонсора(рефа, к которому подвязан юзер в дереве)
     */
    public function actionChange_sponsor(){

        $this->setPageTitle(Yii::app()->config->getPageTitle('Смена спонсора у выбранного пользователя'));

        $model = new ChangeSponsor();

        if(isset($_POST['ChangeSponsor']))
        {
            $model->attributes=$_POST['ChangeSponsor'];

            if($model->validate()){

                //переносим к указанному спонсору - выбранного партнёра
                $model->_modelPartner->moveAsFirst($model->_modelSponsor);

                Yii::app()->user->setFlash('success','Успешно сменили спонсора');

                $this->refresh();
            }
        }
        $this->render('change_sponsor',array('model'=>$model));
    }

    /*
     * доход пользователя от партнёрской программы+потребительской программы
     */
    public function actionProfit(){

        $this->setPageTitle(Yii::app()->config->getPageTitle('Доход'));

        //сумма баллов внесенная пользователями на оплату партнёрских комплектов
        $sql = 'SELECT SUM(tbl_partnership_set.price-tbl_partnership_set.cost_price) AS profit
               FROM `tbl_buying_partnership_set`
                LEFT JOIN tbl_partnership_set ON `tbl_buying_partnership_set`.partnership_set_id=tbl_partnership_set.id';

        $connect = Yii::app()->db;
        $query = $connect->createCommand($sql);
        $result = $query->queryRow();

        //подсчитываем рег. взносы юзеров по партнёрским комплектам
        $sql = 'SELECT SUM(bonuse)as reg_deposit
                FROM {{history_account}}
                WHERE destination=:destination';

        $reg_deposit = $connect->createCommand($sql);
        $reg_deposit->bindValue(':destination', HistoryAccount::DESTIONATION_REG_DEPOSIT, PDO::PARAM_INT);
        $value_reg_depos = $reg_deposit->queryRow();

        // СУММАРНЫЙ ДОХОД=сумма баллов внесенных пользователями на оплату партнерских комплектов – себестоимость партнерских комплектов+сумма на рег. взносы
        $data['suma_partners_bals'] = $result['profit']+$value_reg_depos['reg_deposit'];


        // подсчитаем- Суммарный оборот= сумма баллов внесенных пользователями на оплату партнерских комплектов+рег. взносы
        $sql = 'SELECT SUM(bonuse) AS sum_rev
                FROM {{history_account}}
                WHERE destination=:destination';
        $row_rev = $connect->createCommand($sql);
        $row_rev->bindValue(':destination', HistoryAccount::DESTIONATION_BUY_PARTNER, PDO::PARAM_INT);
        $result_rev = $row_rev->queryRow();

        $data['suma_rev'] = $result_rev['sum_rev']+$value_reg_depos['reg_deposit'];

        $this->render('profit', array(
            'data'=>$data,
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