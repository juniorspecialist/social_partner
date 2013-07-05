<?php
/**
 * Created by JetBrains PhpStorm.
 * User: artem
 * Date: 07.05.13
 * Time: 18:30
 * To change this template use File | Settings | File Templates.
 */

class FinanceController extends BaseUserController {

    public $defaultAction = 'refill';

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
     * пополнение баланса юзера
     * юзер выбираем систему, через которую будет пополнять баланс+вводит сумму пополнения
     */
    public function actionRefill(){

        Yii::import('application.modules.payment.models.*');

        $this->setPageTitle(Yii::app()->config->getPageTitle('Пополнение баланса пользователя'));

        $model = new Billings();
        $robokassa = new Robokassa();
        $robokassa_form = '';

        $model->type_money_system = Billings::TYPE_ROBOKASSA;

        // нажали кнопку - Пополнить, проверим поле ввода суммы, если гуд - формируем форму для отправки данных на Робокассу
        if(Yii::app()->request->isAjaxRequest){

            $model->attributes = $_POST['Billings'];

            if($model->validate()){

                $model->save();

                $robokassa->OutSum = $model->amount;
                $robokassa->InvId = $model->id;

                $robokassa_form = $robokassa->getFormPay();
            }
            $this->renderPartial('refill', array(
                'model'=>$model,
                'robokassa'=>$robokassa,
                'robokassa_form'=>$robokassa_form,
            ));

            Yii::app()->end();
        }

        /*
        if(isset($_POST['Billings'])){

            $model->attributes = $_POST['Billings'];

            if($model->validate()){

                $model->save();

                Yii::app()->user->setFlash('refill','Ваша заявка на вывод средств успешно отправлена');

                $this->refresh();
            }
        }*/

        $this->render('refill', array(
            'model'=>$model,
            'robokassa'=>$robokassa,
            'robokassa_form'=>$robokassa_form,
        ));
    }

    /*
     * отображаем список заявок на вывод средств
     */
    public function actionOutballslist(){

        $this->setPageTitle(Yii::app()->config->getPageTitle('Список заявок на вывод баллов'));

        $model = new Cashout();

        $criteria = new CDbCriteria;

        $criteria->condition = 'partner_id='.Yii::app()->user->id;

        $dataProvider = new CActiveDataProvider('Cashout', array(
           'criteria'=>$criteria,
           'sort'=>array(
               'defaultOrder'=>'create_at DESC',
           ),
           'pagination'=>array(
               'pageSize'=>100,
           ),
        ));

        $this->render('outballs_list', array(
           'dataProvider'=>$dataProvider,
           'model'=>$model,
        ));
    }

    /*
     * заяка на вывод средств из системы
     * возможен для любых юзеров, не только для своих рефералов
     */
    public function actionOutballs(){

        $model = new Cashout();

        $this->setPageTitle(Yii::app()->config->getPageTitle('Заявка на вывод баллов'));

        if(isset($_POST['Cashout']))
        {
            $model->attributes=$_POST['Cashout'];

            if($model->validate())
            {
                $model->save();

                // СПИСЫВАЕМ С БАЛАнса юзера сумму - котор. он указал в заявке
                $sql = 'UPDATE {{partner}} SET balance=balance-:suma WHERE id=:id';
                $connect = Yii::app()->db;
                $query = $connect->createCommand($sql);
                $query->bindValue(':id', $model->partner_id, PDO::PARAM_INT);
                $query->bindValue(':suma', $model->sum_cash, PDO::PARAM_INT);
                $query->execute();

                // теперь запишим в операции по счету - списание с баланса
                $account = new HistoryAccount();
                $account->type_operation = HistoryAccount::TYPE_RASHOD;
                $account->bonuse = $model->sum_cash;
                $account->bonus_sender = $model->partner_id;
                $account->partner_id = $model->partner_id;
                $account->destination = HistoryAccount::DESTIONATION_CASHOUT;
                $account->save();

                Yii::app()->user->setFlash('cashout','Ваша заявка на вывод средств успешно отправлена');

                $this->refresh();
            }
        }

        $this->render('cashout', array(
           'model'=>$model,
            ''
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

        $this->render('history', array(
            'dataProvider'=>$dataProvider,
            'model'=>$model,
        ));
    }
}