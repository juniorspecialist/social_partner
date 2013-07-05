<?php
/**
 * Created by JetBrains PhpStorm.
 * User: artem
 * Date: 28.05.13
 * Time: 11:59
 * To change this template use File | Settings | File Templates.
 */

class AjaxInfoAction extends CAction {
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

        // личные данные по выбранному юзеру из дерева
        if($_POST['type']=='personal'){
            //ID пользователя, ФИО пользователя/E-mail для новичков,Верифицированный телефонный номер
            $model = $this->loadPartner($_POST['id']);
            $this->getController()->renderPartial('_ajax_info_personal', array('model'=>$model));
            Yii::app()->end();
        }

        // бизнесс данные по пользователю
        if($_POST['type']=='business'){

            $partner = $this->loadPartner($_POST['id']);

            //$data[partner_level_from_me]-расстояние от текущего аккаунта до рассматриваемого сотрудника структуры

            // текущий партнёр
            $model = $this->loadPartner();

            $data['partner_level_from_me']= $partner->level - $model->level;

            $this->getController()->renderPartial('_ajax_info_business', array(
                'data'=>$data,
                'partner'=>$partner,
            ));
            Yii::app()->end();
        }
        // прибыль по партнёрской программе
        if($_POST['type']=='profit'){

            //=============разные данные необходимо отображать и разные для юзера=============================
            //для пользователя
            if(Yii::app()->user->role==Partner::ROLE_USER){
                //$data['profit_for_me']-Моя прибыль по Партнерской Программе, сколько денег принём именно мне - выбранный юзер
                $profit = new Profit();

                $data['profit_for_me'] = $profit->inComeProfit('', '', (int)$_POST['id']);
            }

            // для админа
            if(Yii::app()->user->role==Partner::ROLE_ADMIN){

                //сумма баллов_кол-во проданных партнёрских комплектов - profit
                //Внесено средств на оплату партнерских комплектов - общая сумма потраченных денег на покупку партнёрских комплектов(по юзеру) - InputPayMent
                //(SUM(tbl_partnership_set.price)+400)  - вручную добавляем рег. взнос к сумме потраченных денег на покупку партнёрских комплектов
                $sql = 'SELECT SUM(tbl_partnership_set.price-tbl_partnership_set.cost_price) AS profit,
                        (SUM(tbl_partnership_set.price)+400) AS InputPayMent
               FROM `tbl_buying_partnership_set`
                LEFT JOIN tbl_partnership_set ON `tbl_buying_partnership_set`.partnership_set_id=tbl_partnership_set.id
                WHERE tbl_buying_partnership_set.who_buys=:user_id';

                $connect = Yii::app()->db;

                $query = $connect->createCommand($sql);

                $query->bindValue(':user_id', $_POST['id'], PDO::PARAM_INT);

                $query_result = $query->queryRow();

                //Внесено средств на оплату партнерских комплектов
                $data['inComePaymentByPartner'] = empty($query_result['InputPayMent'])?0:$query_result['InputPayMent'];

                //Суммарная прибыль с пользователя от продажи Партнерских комплектов
                $data['sumProfitFromUserByPartnerShip'] = empty($query_result['profit'])?0:$query_result['profit'];


                //Всего внесено средств на счет=сумма всех пополнений+сумма переводов ему средств
//                $sql = 'SELECT SUM(bonuse) AS all_bonuse
//                        FROM {{history_account}}
//                        WHERE type_operation=:type_operation  AND partner_id=:partner_id';
//
//                $query_ = $connect->createCommand($sql);
//                $query_->bindValue(':type_operation', HistoryAccount::TYPE_PRIHOD, PDO::PARAM_INT);
//                $query_->bindValue(':partner_id', $_POST['id'], PDO::PARAM_INT);
//                $result = $query_->queryRow();
//
//                $data['AllinputAccountBalance'] = empty($result['all_bonuse'])?0:$result['all_bonuse'];

                //Текущий остаток на счете
                $data['balance'] = Partner::getBalance((int)$_POST['id']);
            }

            $this->getController()->renderPartial('_ajax_info_profit', array(
                'data'=>$data,
            ));
            Yii::app()->end();
        }

        throw new CHttpException(400,'The requested page does not exist.');
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