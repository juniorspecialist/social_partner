<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Саня
 * Date: 29.05.13
 * Time: 21:53
 * To change this template use File | Settings | File Templates.
 */
class TransferAction extends CAction {

    public function run()
    {
        $model = new TransferModel();
        $model->scenario = 'transfer';

        if(isset($_POST['TransferModel']) && Yii::app()->request->isAjaxRequest)
        {
            if(isset($_POST['TransferModel']['smsCode'])){
                $model->scenario = 'sms';
            }else{
                $model->scenario = 'transfer';
            }

            $model->attributes = $_POST['TransferModel'];

            if($model->validate()){

                if($model->scenario=='transfer'){

                    //отправляем СМС-сообщение с кодом, для успешной авторизации
                    // если админ, то используем номер мобильного из настроек системы
                    if(Yii::app()->user->role==Partner::ROLE_ADMIN){
                        $user = new Partner();
                        $user->phoneSms = Yii::app()->config->get('ADMIN.PHONE1');
                    }else{
                        $user = Partner::model()->findByPk(Yii::app()->user->id);
                        $user->phoneSms = $user->phone;
                    }

                    $user->rndSmsCode();
                    $user->textSms = 'Код:'.$user->codeSms;

                    // проверяем параметры для отправки СМС
                    $user->checkParams();

                    //и отправляем СМС
                    $user->sendSms();

                    //- true - сообщение доставлено, false -  не досталено(в error_descSms - описание ошибки)
                    //if(!$user->isDeliveredSms()){
                    //    $model->addError('smsCode', 'При отправке смс, возникли проблемы-'.$user->error_descSms);
                    //}

                    $this->getController()->renderPartial('application.extensions.actions.view._transfer', array(
                        'model'=>$model,
                        'showFileds' => true,
                    ));
                }else{

                    $showFileds = false;

                    // проверили данные формы+проверили корректность смс-кода
                    if($model->validate()){

                        // фиксируем операции списания и перевода по балансам юзеров
                        $this->fixTransfer($model);

                        Yii::app()->user->setFlash('success','Перевод совершен успешно');

                        echo 'ok';
                    }
                }

            }else{
                if($model->scenario=='transfer'){
                  $showFileds = false;
                }else{
                  $showFileds = true;
                }
                $this->getController()->renderPartial('application.extensions.actions.view._transfer', array(
                      'model'=>$model,
                      'showFileds' => $showFileds,
                    )
                );
            }

            Yii::app()->end();
        }

        $this->getController()->render('application.extensions.actions.view._transfer', array(
              'model'=>$model,
              'showFileds' => false,
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

    /*
     * обновляем данные и записываем перевод
     */
    private function fixTransfer($model){
        //записываем операцию пополнения баланса юзера
        $connection=Yii::app()->db; // так можно сделать, если в конфигурации описан компонент соединения "db"
        $command=$connection->createCommand('UPDATE {{partner}} SET balance=balance+:suma WHERE id=:id');
        $command->bindValue(':id', $model->to_partner, PDO::PARAM_INT );
        $command->bindValue(':suma', $model->sum_transfer, PDO::PARAM_INT );
        $command->execute();

        //списываем с баланса юзера, который переводит бабло
        $query = $connection->createCommand('UPDATE {{partner}} SET balance=balance-:suma WHERE id=:id');
        $query->bindValue(':id', Yii::app()->user->id, PDO::PARAM_INT );
        $query->bindValue(':suma', $model->sum_transfer, PDO::PARAM_INT );
        $query->execute();


        // запишим операцию перевода в операции с балансом пользователя
        $account = new HistoryAccount();
        // тии операции
        $account->type_operation = HistoryAccount::TYPE_PRIHOD;
        // получатель бонуса
        $account->partner_id = $model->to_partner;
        //отправитель
        $account->bonus_sender = Yii::app()->user->id;
        //назначение операции - платежа
        $account->destination = HistoryAccount::DESTIONATION_TRANSFER;
        // сумма платежа в истории платежей
        $account->bonuse = $model->sum_transfer;
        $account->save();

        // теперь спишим с баланса тек. юзера сумму перевода
        $transfer = new HistoryAccount();
        $transfer->type_operation = HistoryAccount::TYPE_RASHOD;
        // получатель бонуса
        $transfer->partner_id = $model->to_partner;
        //отправитель
        $transfer->bonus_sender = Yii::app()->user->id;
        //назначение операции - платежа
        $transfer->destination = HistoryAccount::DESTIONATION_TRANSFER;
        // сумма платежа в истории платежей
        $transfer->bonuse = $model->sum_transfer;
        $transfer->save();
    }
}