<?php
/**
 * Created by JetBrains PhpStorm.
 * User: artem
 * Date: 12.06.13
 * Time: 16:24
 * To change this template use File | Settings | File Templates.
 */

/*
 * вызов php /var/www/secret/cron.php smsinvitation "sms_send"

 */
class SmsInvitationCommand extends CConsoleCommand {

    // лимит на отправку смс-сообщений за один раз запуска
    const SEND_LIMIT = 10;

    //лимит опрашивания статуса смс, при 1 запуске
    const SHIP_LIMIT = 10;

    // тип действия, котор. нужно выполнить по смс сообщениям
    public $type;

    public function run($type='') {

        $this->type = $type[0];

        $connect = Yii::app()->db;

        //на основании указанного типа действия выполняем операцию - либо отправляем смс-сообщения из очереди, либо проверяем их доставку
        if($this->type=='sms_send'){// отправляем смс-сообщения

            $sql = 'SELECT * FROM {{invitations}} WHERE status=:status LIMIT :limit';

            $query = $connect->createCommand($sql);

            $query->bindValue(':status', Invitations::STATUS_CREATE, PDO::PARAM_INT);
            $query->bindValue(':limit', self::SEND_LIMIT , PDO::PARAM_INT);

            $sms_list = $query->queryAll();

            // обрабатываем список созданных смс, производим их отправку и записываем их ID, для проверки статуса
            foreach($sms_list as $sms_row){
                //отправляем СМС-сообщение с кодом, для успешной авторизации
                $sms = Yii::app()->sms;
                $sms->phone = $sms_row['phone'];
                $sms->text = $sms_row['invitations_text'];
                // проверяем параметры для отправки СМС и отправляем СМС
                $sms->checkParams();
                $sms->sendSms();

                if($sms->error){
                    $sql_update = 'UPDATE {{invitations}} SET status=:status,service_id=:service_id  WHERE id=:id';
                    $status = Invitations::STATUS_ERROR_SHIP;
                }else{
                    $sql_update = 'UPDATE {{invitations}} SET status=:status,service_id=:service_id WHERE id=:id';
                    $status = Invitations::STATUS_SEND;
                }
                $query_update = $connect->createCommand($sql_update);
                $query_update->bindValue(':id', $sms_row['id'], PDO::PARAM_INT);
                $query_update->bindValue(':status', $status, PDO::PARAM_INT);
                $query_update->bindValue(':service_id', $sms->getIdSms(), PDO::PARAM_STR);

                $query_update->execute();

                unset($sms);
            }
        }

        // проверяем доставку смс сообщений по их ID от сервиса+ записываем статус, если были ошибки
        if($this->type=='ship'){

            $sql = 'SELECT * FROM {{invitations}} WHERE status=:status LIMIT :limit';

            $query = $connect->createCommand($sql);

            $query->bindValue(':status', Invitations::STATUS_SEND, PDO::PARAM_INT);
            $query->bindValue(':limit', self::SHIP_LIMIT, PDO::PARAM_INT);

            $sms_list = $query->queryAll();

            foreach($sms_list as $send_sms){

                $sms = Yii::app()->sms;

                $sms->setIdSms($send_sms['service_id']);

                $status_result = $sms->getStatusSms();

                //доставлено
                if($status_result){
                    $sql = 'UPDATE {{invitations}} SET status=:status WHERE id=:id';
                    $query_update_status = $connect->createCommand($sql);
                    $query_update_status->bindValue(':status', Invitations::STATUS_SHIP, PDO::PARAM_INT);
                    $query_update_status->bindValue(':id', $send_sms['id'], PDO::PARAM_INT);
                    $query_update_status->execute();
                }else{
                    //если-FALSE и нет ошибки, тогда -Сообщение находится в очереди
                    if(!$sms->error){
                        //Сообщение находится в очереди,  не обновляем статус
                    }else{
                        //а если ошибки - то значит пишим информацию об ошибке
                        $sql = 'UPDATE {{invitations}} SET status=:status WHERE id=:id';
                        $query_update_status = $connect->createCommand($sql);
                        $query_update_status->bindValue(':status', Invitations::STATUS_ERROR_SHIP, PDO::PARAM_INT);
                        $query_update_status->bindValue(':id', $send_sms['id'], PDO::PARAM_INT);
                        $query_update_status->execute();
                    }
                }

                unset($sms);
            }
        }
    }
}