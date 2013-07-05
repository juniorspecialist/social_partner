<?php
/**
 * Created by JetBrains PhpStorm.
 * User: artem
 * Date: 06.05.13
 * Time: 17:26
 * To change this template use File | Settings | File Templates.
 */
/*
 * отправка смс:
 * $model->checkParams();
 * $model->sendSms();
 * $model->isDeliveredSms();- true - сообщение доставлено, false -  не досталено(в error_descSms - описание ошибки)
 */
class SMSFeedbackBehavior extends CActiveRecordBehavior{

    //amazing1
    protected $loginService = 'amazing1';//логин доступа к СМС сервису http://www.smsfeedback.ru/

    //zx78op01
    protected $passService = 'zx78op01';//пароль доступа к СМС сервису http://www.smsfeedback.ru/

    public $errorSms = false;//флаг наличия ошибок
    public $error_descSms;// текстовое описание ошибки
    public $urlSms;//адрес запроса, для отправки и получения данных от смс-сервиса
    public $balanceSms;// баланс пользователя
    public $valute;// валюта пользователя

    public $hostSms='api.smsfeedback.ru';// хост сайта, АПИ котор. мы будем использовать
    public $portSms = 80;// какой порт исползовать при отправке GET запроса через сокеты
    public $phoneSms;// номер телефона, куда отправляем СМС
    public $senderSms = false;
    public $textSms;//текст сообщения
    public $wapurlSMs = false;
    public $codeSms;// код смс, котор. отправили юзеру, для подтверждения операции
    public $salt = 15975333;

    private  $id_sms;//ID смс на сервере отправки смс-сообщений
    private $status_sms;// статус смс сообщения

    /*
     * проверяем необходмсые параметры , на указание - валидация
     */
    public function checkParams($type = 'sms'){

        //$this->loginService = 'amazing1';
        //$this->passService = 'zx78op01';

        if(empty($this->loginService)){ $this->errorSms = true; $this->error_descSms.='Не указан логин к смс-сервису.'; }

        if(empty($this->passService)){ $this->errorSms = true; $this->error_descSms.='Не указан пароль к смс-сервису.'; }

        if($type=='sms'){
            if(empty($this->textSms)){ $this->errorSms = true; $this->error_descSms.='Не указан текст сообщения.'; }
        }

        // проверим длину сообщения
        if(!$this->errorSms){

            if(strlen($this->textSms)>70){
                $this->errorSms = true;
                $this->error_descSms.='ДЛина смс превышает 70 символов';
            }
        }

        //если не было ошибок, проверка прошла успешно, формируем адрес для отправки запроса
        if(!$this->errorSms){
            $this->urlSms = 'http://'.$this->loginService.':'.$this->passService.'@api.smsfeedback.ru/messages/v2/';
        }
    }

    /*
     * ID смс-сообщения, по которому проверим статус, доставлено или не доставлено, или ошибка и т.д.
     */
    public function setIdSms($id_sms){
        $this->id_sms = $id_sms;
    }

    /*
    * функция - генератор паролей для смс
    */

    public function getIdSms(){
        return $this->id_sms;
    }

    // Параметр $number - сообщает число символов в пароле

    function rndSmsCode($number = 5)
    {
        $arr = array('0','1','2','3','4','5','6','7','8','9');

        // Генерируем пароль для смс
        $pass = "";
        for($i = 0; $i < $number; $i++)
        {
            // Вычисляем произвольный индекс из массива
            $index = rand(0, count($arr) - 1);
            $pass .= $arr[$index];
        }

        $this->codeSms = $pass;

        Yii::app()->session->add('smsCode', md5($pass.$this->salt));

        return $pass;
    }

    /*
     * проверим на валидацию код смс из сессии и с тем, что юзер ввёл в поле для подтверждения
     */
    public function isValidateSmsCode($userSmsCode){
        if(md5($userSmsCode.$this->salt)==Yii::app()->session->get('smsCode')){
            return true;
        }else{
            return false;
        }
    }

    /*
     * информация о текущем балансе пользователя
     * Проверка состояния счета
     */
    public function getBalance(){

        //params validation
        $this->checkParams($type = 'balance');

        //добавляем недостающий параметр для запроса
        if(!$this->errorSms){

            $this->urlSms.='balance/';

            // отправляем запрос и обрабатываем ответ, от сайта
            //де в каждой строке 1 значение – тип баланса, 2 значение – баланс, 3 значение – кредит (возможность использовать сервис при отрицательном балансе)
            $result = file_get_contents($this->urlSms);

            $explode_result = explode(';', $result);

            if(isset($explode_result[1])){
                $this->balanceSms = $explode_result[1];
                $this->valute = $explode_result[0];
            }else{
                $this->balanceSms = 'undefined';
                $this->valute = 'undefined';
            }
        }
    }

    /*
     * фун-я отправки СМС-сообщения
     * sendSms();
     */
    public function sendSms($errno = '', $errstr = ''){
        if(!$this->errorSms){
            $fp = fsockopen($this->hostSms, $this->portSms, $errno, $errstr);
            if (!$fp) {
                $this->errorSms = true;
                $this->error_descSms = "errno: $errno \nerrstr: $errstr\n";
                //return "errno: $errno \nerrstr: $errstr\n";
            }else{
                fwrite($fp, "GET /messages/v2/send/" .
                    "?phone=" . rawurlencode($this->phoneSms) .
                    "&text=" . rawurlencode($this->textSms) .
                    ($this->senderSms ? "&sender=" . rawurlencode($this->senderSms) : "") .
                    ($this->wapurlSMs ? "&wapurl=" . rawurlencode($this->wapurlSMs) : "") .
                    "  HTTP/1.0\n");
                fwrite($fp, "Host: " . $this->hostSms . "\r\n");
                if ($this->loginService != "") {
                    fwrite($fp, "Authorization: Basic " .
                        base64_encode($this->loginService. ":" . $this->passService) . "\n");
                }
                fwrite($fp, "\n");
                $response = "";
                while(!feof($fp)) {
                    $response .= fread($fp, 1);
                }
                fclose($fp);
                list($other, $responseBody) = explode("\r\n\r\n", $response, 2);

                //echo 'responseBody='.$responseBody.'<br>'; flush();

                // проверим результат отправки запроса
                $this->checkResponse($responseBody);

            }
        }
    }

    /*
     * проверяем результат отправки смс сообщения, по ответу смс-сервиса
     */
    public function checkResponse($response){

        //анализируем ответ от запроса на отправку смс
        $expl = explode(';', $response);

        //сообщение успешно отправлено
        if($expl[0]!='accepted'){

            $this->error = true;
            if($expl[0]=='invalid mobile phone'){
                $this->error_descSms.='Неверно задан номер телефона (формат 71234567890).';
            }
            if($expl[0]=='text is empty'){
                $this->error_descSms.='Отсутствует текст.';
            }
            if($expl[0]=='text must be string'){
                $this->error_descSms.='Текст не на латинице или не в utf-8 .';
            }
            if($expl[0]=='sender address invalid'){
                $this->error_descSms.='Неверная (незарегистрированная) подпись отправителя.';
            }
            if($expl[0]=='wapurl invalid'){
                $this->error_descSms.='Неправильный формат wap-push ссылки.';
            }
            if($expl[0]=='invalid schedule time format'){
                $this->error_descSms.='Неверный формат даты отложенной отправки сообщения.';
            }
            if($expl[0]=='invalid status queue name'){
                $this->error_descSms.='Неверное название очереди статусов сообщений.';
            }
            if($expl[0]=='not enough balance'){
                $this->error_descSms.='Баланс пуст (проверьте баланс).';
            }

            //if(empty($this->error_desc)){ $this->error_descSms.='undefined error.'; }

            return false;// ошибка при отправке смс

        }else{

            // возвращаем ID успешно отправленного сообщения через СМС
            //return $expl[1];
            $this->id_sms = $expl[1];
        }
    }

    /*
     * сообщение доставлено - TRUE
     * сообщение НЕ доставлено есть ошибки - FALSE, описание ошибки в - $this->error_descSms
     */
    public function isDeliveredSms(){

        // цикл по попыткам
        for($i=0;$i<30;$i++){

            if(!empty($this->id_sms) && !$this->errorSms){

                // проверим доставку СМС-сообщения до адресата
                $resultDelivered = $this->getStatusSms();

                if($resultDelivered){
                    return true;
                }
            }

            if($this->errorSms){
                //echo 'hae_errors--'.$this->error_descSms.'<br>';
                return false;
            }

            sleep(1);
        }

    }

    /*
     * проверка состояния отправленного сообщения на сервере
     * ответ - A132571BC;delivered
     */
    public function getStatusSms(){

        // если ест ошибки, то не отправляем проверку доставки смс сообщения
        if($this->errorSms){ echo 'hae_errors-'.$this->error_descSms.'<br>';  return false; }

        //http://login:password@api.smsfeedback.ru/messages/v2/status/?id=A132571BC
        // адрес для отправки запроса на получение статуса СМС-сообщения
        $url = 'http://'.$this->loginService.':'.$this->passService.'@api.smsfeedback.ru/messages/v2/status/?id='.$this->id_sms;

        $result = file_get_contents($url);

        //echo 'result_send_sms='.$result.'<br>';

        $exp_result = explode(';', $result);

        //Сообщение находится в очереди
        if($exp_result[1]=='queued'){
            return false;
        }
        // сообщение доставлено абоненту
        if($exp_result[1]=='delivered'){
            return true;
        }
        //Ошибка доставки SMS (абонент в течение времени доставки находился вне зоны действия сети или номер абонента заблокирован)
        if($exp_result[1]=='delivery error'){
            $this->errorSms = true;
            $this->error_descSms.='Ошибка доставки SMS (абонент в течение времени доставки находился вне зоны действия сети или номер абонента заблокирован)';
            return false;
        }
        //Сообщение доставлено в SMSC
        if($exp_result[1]=='smsc submit'){
            return false;
        }
        //Сообщение отвергнуто SMSC (номер заблокирован или не существует)
        if($exp_result[1]=='smsc reject'){
            $this->errorSms = true;
            $this->error_descSms.='Сообщение отвергнуто SMSC (номер заблокирован или не существует)';
            return false;
        }
        //Неверный идентификатор сообщения
        if($exp_result[1]=='incorrect id'){
            $this->errorSms = true;
            $this->error_descSms.='Неверный идентификатор сообщения';
            return false;
        }
    }

}