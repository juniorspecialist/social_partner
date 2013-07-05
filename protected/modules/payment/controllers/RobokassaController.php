<?php
/**
 * Created by JetBrains PhpStorm.
 * User: artem
 * Date: 30.05.13
 * Time: 13:13
 * To change this template use File | Settings | File Templates.
 */
class RobokassaController extends Controller{

    public $layout = '//layouts/column2';

    public function filters()
    {
        return array(
            'accessControl',
        );
    }

    public function accessRules()
    {
        return array(
            array('allow',
                //'actions'=>array('delete'),
                'verbs'=>array('POST'),
                'users'=>array('*'),
                //'roles'=>array('admin'),
            ),
            array('deny',
                //'actions'=>array('delete'),
                'users'=>array('*'),
            ),
        );
    }
    /*
     * используется для оповещения о платеже
     */
    public function actionResult(){

        $model = new Robokassa();
        $model->scenario = 'result';

        $model->OutSum = $_POST['OutSum'];
        $model->InvId = $_POST['InvId'];
        $model->SignatureValue = $_POST['SignatureValue'];

        if($model->validate()){

            // поиск заявки на пополнение счёта
            $billing = Billings::model()->findByPk($model->InvId);
            $billing->status = Billings::STATUS_PAID;
            // обновили статус у заявки
            $billing->save();

            // добавим пополнение баланса по юзеру
            $sql = 'UPDATE {{partner}} SET balance=balance+:suma WHERE id=:id';
            $connect = Yii::app()->db;

            // обновили баланс юзера
            $query_user = $connect->createCommand($sql);
            $query_user->bindValue(':id', $billing->partner_id, PDO::PARAM_INT);
            $query_user->bindValue(':suma', $billing->amount, PDO::PARAM_INT);
            $query_user->execute();

            //теперь запишим операцию пополнения баланса в историю счёта
            $account = new HistoryAccount();
            // тии операции
            $account->type_operation = HistoryAccount::TYPE_PRIHOD;
            // получатель бонуса
            $account->partner_id = $billing->partner_id;
            //отправитель
            $account->bonus_sender = Yii::app()->config->get('ADMIN.ACCOUNT');
            //назначение операции - платежа
            $account->destination = HistoryAccount::DESTIONATION_ADD_BALANCE;
            // сумма платежа в истории платежей
            $account->bonuse = $billing->amount;
            $account->save();

            echo 'OK'.$_POST['InvId'];

        }else{
            echo 'error'.$_POST['InvId'];
        }

        Yii::app()->end();
    }

    /*
     * Success URL:[используется в случае успешного проведения платежа]
     */
    public function actionSuccess(){

        $model = new Robokassa();
        $model->scenario = 'success';

        $model->OutSum = $_POST['OutSum'];
        $model->InvId = $_POST['InvId'];
        $model->SignatureValue = $_POST['SignatureValue'];

        //if($model->validate()){
            $this->render('success', array(
                'model'=>$model,
            ));
        /*}else{
            $this->render('fail', array(
                'model'=>$model,
            ));
        }*/
    }

    /*
     * Fail URL: [используется в случае отказа проведения платежа]
     */
    public function actionFail(){

        $model = new Robokassa();

        $this->render('fail', array(
           'model'=>$model,
        ));
    }
}