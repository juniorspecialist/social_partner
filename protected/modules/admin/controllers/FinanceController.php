<?php
/**
 * Created by JetBrains PhpStorm.
 * User: artem
 * Date: 07.05.13
 * Time: 18:30
 * To change this template use File | Settings | File Templates.
 */

class FinanceController extends BaseAdminController {

    public function actions(){
        return array(
           /*
             * перевод денежных средств другим пользователям системы
             */
            'transfer'=>array(
                'class'=>'ext.actions.TransferAction',
            )
        );
    }

    public function actionIndex(){

        $this->render('index');
    }

    /*
     * Движение средств в системе
     */
    public function actionAction_finance(){

        $this->setPageTitle(Yii::app()->config->getPageTitle('Движение средств в системе'));

        $model = new HistoryAccount();

        //$criteria = new CDbCriteria;

        //$criteria->condition = 'partner_id='.Yii::app()->user->id.' OR bonus_sender='.Yii::app()->user->id;

        $dataProvider = new CActiveDataProvider('HistoryAccount', array(
            //'criteria'=>$criteria,
            'sort'=>array(
                'defaultOrder'=>'create_at DESC',
            ),
            'pagination'=>array(
                'pageSize'=>100,
            ),
        ));

        $this->render('history', array(
            'dataProvider'=>$dataProvider,
            'model'=>$model,
        ));

    }

    /*
     * история счёта
     */
    public function actionHistory(){

        $this->setPageTitle(Yii::app()->config->getPageTitle('История счета'));

        $model = new HistoryAccount();
        $criteria = new CDbCriteria;
        $criteria->condition = '(type_operation='.HistoryAccount::TYPE_RASHOD.' AND bonus_sender='.Yii::app()->user->id.') OR (type_operation='.HistoryAccount::TYPE_PRIHOD.' AND partner_id='.Yii::app()->user->id.')';

        $dataProvider = new CActiveDataProvider('HistoryAccount', array(
            'criteria'=>$criteria,
            'sort'=>array(
                'defaultOrder'=>'t.create_at DESC',
            ),
            'pagination'=>array(
                'pageSize'=>100,
            ),
        ));

        $this->render('action_finance', array(
            'dataProvider'=>$dataProvider,
            'model'=>$model,
        ));
    }

    /*
     * ввод наличных средств
     */
    public function actionInputBalls(){

        $this->setPageTitle(Yii::app()->config->getPageTitle('Ввод наличных средств'));

        if(isset($_POST['sum'])){

            //echo '<pre>'; print_r($_POST); die();
            $connect = Yii::app()->db;

            for($i=0;$i<count($_POST['sum']);$i++){
                // сумма
                $row_sum = $_POST['sum'][$i];

                //ID партнёра
                $row_partner = $_POST['id'][$i];

//                echo 'sum='.$row_sum.'|partne='.$row_partner.'<br>';
//                continue;

                $model = new InputCashMoney();
                $model->partner_id = $row_partner;
                $model->sum = $row_sum;
                if($model->validate()){

                    //фиксируем операцию пополнения баланса
                    $sql = 'UPDATE {{partner}} SET balance=balance+:suma WHERE id=:id';
                    $query = $connect->createCommand($sql);
                    $query->bindValue(':id', $model->partner_id, PDO::PARAM_INT);
                    $query->bindValue(':suma', $model->sum, PDO::PARAM_INT);
                    $query->execute();

                    //запишим в лог пополнение баланса у юзера
                    $account = new HistoryAccount();
                    $account->type_operation = HistoryAccount::TYPE_PRIHOD;
                    $account->bonuse = $model->sum;
                    $account->bonus_sender = Yii::app()->config->get('ADMIN.ACCOUNT');
                    $account->partner_id = $model->partner_id;
                    $account->destination = HistoryAccount::DESTIONATION_TRANSFER;
                    $account->save();
                }
//                die();
                Yii::app()->user->setFlash('inputmoney','Пополнение баласа, у выбранных пользователей, прошло успешно.');
                $this->refresh();
            }
        }


        $this->render('input_money');
    }

    /*
     *Заявки на вывод средств, заполненные пользователем, падают в аккаунт админа
     */
    public function actionOutputBalls(){

        $this->setPageTitle(Yii::app()->config->getPageTitle('Заявки на вывод средств'));


        $model = new Cashout();

         $dataProvider = new CActiveDataProvider('Cashout', array(
             //'criteria'=>$criteria,
             'sort'=>array(
                 'defaultOrder'=>'t.create_at DESC',
             ),
             'pagination'=>array(
                 'pageSize'=>100,
             ),
         ));

         $this->render('output_money', array(
             'dataProvider'=>$dataProvider,
             'model'=>$model,
         ));
    }

    /*
     * изменение статуса заявки на вывод средств
     */
    public function actionToggle($id)
    {
        if(!Yii::app()->request->isAjaxRequest){
            throw new CHttpException(400,'Не корректный запрос');
        }

        //$model = Cashout::model()->findByPk($id);

        if(!empty($id)){
            $connect = Yii::app()->db;
            $query = $connect->createCommand('UPDATE {{cashout}} SET status=:status WHERE id=:id');
            $query->bindValue(':id', $id, PDO::PARAM_INT);
            $query->bindValue(':status', Cashout::STATUS_ACCEPT, PDO::PARAM_INT);
            // установим новый статус у заявки на вывод денег
            $query->execute();
        }

        if (!Yii::app()->request->isAjaxRequest)
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }
}