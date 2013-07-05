<?php
/**
 * Created by JetBrains PhpStorm.
 * User: artem
 * Date: 13.05.13
 * Time: 17:03
 * To change this template use File | Settings | File Templates.
 */

class ProfitController extends BaseAdminController {

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

        $this->setPageTitle(Yii::app()->config->getPageTitle('доходы по партнерской программе'));

        $connect = Yii::app()->db;

        $sql = 'SELECT tbl_partner.*, COUNT(tbl_buying_partnership_set.id) AS count
                       FROM `tbl_partner`
                        LEFT JOIN tbl_buying_partnership_set ON tbl_partner.id=`tbl_buying_partnership_set`.who_buys
        group by tbl_partner.id';

        $count = Yii::app()->db->createCommand('SELECT COUNT(id) AS count FROM {{partner}}')->queryScalar();

        $dataProvider = new CSqlDataProvider($sql, array(
            'totalItemCount'=>$count,
            'pagination'=>array(
                'pageSize'=>100,
            )
        ));

        // подсчитаем кол-во баллов потраченных на рег. взносы по всем юзерам
        $sql = 'SELECT SUM(bonuse)as reg_deposit
                FROM {{history_account}}
                WHERE destination=:destination';
        $reg_deposit = $connect->createCommand($sql);
        //$reg_deposit->bindValue(':type_operation', HistoryAccount::TY,);
        $reg_deposit->bindValue(':destination', HistoryAccount::DESTIONATION_REG_DEPOSIT, PDO::PARAM_INT);
        $value_reg_depos = $reg_deposit->queryRow();

       //сумма баллов_кол-во проданных партнёрских комплектов
        $sql = 'SELECT COUNT(`tbl_buying_partnership_set`.id) as count, SUM(tbl_partnership_set.price) AS price
               FROM `tbl_buying_partnership_set`
                LEFT JOIN tbl_partnership_set ON `tbl_buying_partnership_set`.partnership_set_id=tbl_partnership_set.id';

        $data = $connect->createCommand($sql)->queryRow();
        $data['price'] = $data['price']+$value_reg_depos['reg_deposit'];


        // подсчитаем кол-во неименных партнёрских комплектов
        $sql = 'SELECT COUNT(id) AS count_no_name_partner
                FROM {{buying_partnership_set}}
                WHERE type_buying=:type_buying';
        $query_noname = $connect->createCommand($sql);
        $query_noname->bindValue(':type_buying', BuyingPartnershipSet::TYPE_NONAME, PDO::PARAM_INT);
        $result_no_name_partners = $query_noname->queryRow();

        $data['count_no_name_partner']  = $result_no_name_partners['count_no_name_partner'];

        $this->render('partner', array(
            'dataProvider'=>$dataProvider,
            'data'=>$data,
        ));
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