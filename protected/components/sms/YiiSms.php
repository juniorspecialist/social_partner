<?php
/**
 * Created by JetBrains PhpStorm.
 * User: artem
 * Date: 13.06.13
 * Time: 16:11
 * To change this template use File | Settings | File Templates.
 */

class YiiSms  extends CApplicationComponent{

    public $login;
    public $pass;

    public $error = false;//флаг наличия ошибок
    public $error_desc;// текстовое описание ошибки
    public $url;//адрес запроса, для отправки и получения данных от смс-сервиса
    public $balance;// баланс пользователя
    public $valute;// валюта пользователя

    public $host='api.smsfeedback.ru';// хост сайта, АПИ котор. мы будем использовать
    public $port = 80;// какой порт исползовать при отправке GET запроса через сокеты
    public $phone;// номер телефона, куда отправляем СМС
    public $sender = false;
    public $text;//текст сообщения
    public $wapurl = false;
    public $code;// код смс, котор. отправили юзеру, для подтверждения операции
    public $salt;

    public $id_sms;//ID смс на сервере отправки смс-сообщений
    public $status_sms;// статус смс сообщения


    public function findError($desc){
        $this->error = true;
        $this->error_desc = $desc;
    }

    /*
     * проверяем необходмсые параметры , на указание - валидация
     */
    public function checkParams($type = 'sms'){

        if(empty($this->login)){ $this->findError('Не указан логин к смс-сервису.'); }

        if(empty($this->pass)){ $this->findError('Не указан пароль к смс-сервису.'); }

        if($type=='sms'){
            if(empty($this->text)){ $this->findError('Не указан текст сообщения.'); }
        }

        // проверим длину сообщения
        if(!$this->error){
            if(strlen($this->text)>70){
                $this->findError('ДЛина смс превышает 70 символов');
            }
        }

        //если не было ошибок, проверка прошла успешно, формируем адрес для отправки запроса
        if(!$this->error){
            $this->url = 'http://'.$this->login.':'.$this->pass.'@api.smsfeedback.ru/messages/v2/';
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

        $this->code = $pass;

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
        if(!$this->error){

            $this->url.='balance/';

            // отправляем запрос и обрабатываем ответ, от сайта
            //де в каждой строке 1 значение – тип баланса, 2 значение – баланс, 3 значение – кредит (возможность использовать сервис при отрицательном балансе)
            $result = file_get_contents($this->url);

            $explode_result = explode(';', $result);

            if(isset($explode_result[1])){
                $this->balance = $explode_result[1];
                $this->valute = $explode_result[0];
            }else{
                $this->balance = 'undefined';
                $this->valute = 'undefined';
            }
        }
    }

    /*
     * фун-я отправки СМС-сообщения
     * sendSms();
     */
    public function sendSms($errno = '', $errstr = ''){
        if(!$this->error){
            $fp = fsockopen($this->host, $this->port, $errno, $errstr);
            if (!$fp) {
                $this->findError("errno: $errno \nerrstr: $errstr\n");
            }else{
                fwrite($fp, "GET /messages/v2/send/" .
                    "?phone=" . rawurlencode($this->phone) .
                    "&text=" . rawurlencode($this->text) .
                    ($this->sender ? "&sender=" . rawurlencode($this->sender) : "") .
                    ($this->wapurl ? "&wapurl=" . rawurlencode($this->wapurl) : "") .
                    "  HTTP/1.0\n");
                fwrite($fp, "Host: " . $this->host . "\r\n");
                if ($this->login != "") {
                    fwrite($fp, "Authorization: Basic " .
                        base64_encode($this->login. ":" . $this->pass) . "\n");
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

            if($expl[0]=='invalid mobile phone'){
                $this->findError('Неверно задан номер телефона (формат 71234567890).');
            }
            if($expl[0]=='text is empty'){
                $this->findError('Отсутствует текст.');
            }
            if($expl[0]=='text must be string'){
                $this->findError('Текст не на латинице или не в utf-8 .');
            }
            if($expl[0]=='sender address invalid'){
                $this->findError('Неверная (незарегистрированная) подпись отправителя.');
            }
            if($expl[0]=='wapurl invalid'){
                $this->findError('Неправильный формат wap-push ссылки.');
            }
            if($expl[0]=='invalid schedule time format'){
                $this->findError('Неверный формат даты отложенной отправки сообщения.');
            }
            if($expl[0]=='invalid status queue name'){
                $this->findError('Неверное название очереди статусов сообщений.');
            }
            if($expl[0]=='not enough balance'){
                $this->findError('Баланс пуст (проверьте баланс).');
            }

            return false;// ошибка при отправке смс

        }else{

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

            if(!empty($this->id_sms) && !$this->error){

                // проверим доставку СМС-сообщения до адресата
                $resultDelivered = $this->getStatusSms();

                if($resultDelivered){
                    return true;
                }
            }

            if($this->error){
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
        if($this->error){ return false; }

        //http://login:password@api.smsfeedback.ru/messages/v2/status/?id=A132571BC
        // адрес для отправки запроса на получение статуса СМС-сообщения
        $url = 'http://'.$this->login.':'.$this->pass.'@api.smsfeedback.ru/messages/v2/status/?id='.$this->id_sms;

        $result = file_get_contents($url);

        $exp_result = explode(';', $result);

        //Сообщение находится в очереди
        if($exp_result[1]=='queued'){ return false;}

        // сообщение доставлено абоненту
        if($exp_result[1]=='delivered'){ return true;}

        //Ошибка доставки SMS (абонент в течение времени доставки находился вне зоны действия сети или номер абонента заблокирован)
        if($exp_result[1]=='delivery error'){
            $this->findError('Ошибка доставки SMS (абонент в течение времени доставки находился вне зоны действия сети или номер абонента заблокирован)');
            return false;
        }
        //Сообщение доставлено в SMSC
        if($exp_result[1]=='smsc submit'){ return false;}

        //Сообщение отвергнуто SMSC (номер заблокирован или не существует)
        if($exp_result[1]=='smsc reject'){
            $this->findError('Сообщение отвергнуто SMSC (номер заблокирован или не существует)');
            return false;
        }
        //Неверный идентификатор сообщения
        if($exp_result[1]=='incorrect id'){
            $this->findError('Неверный идентификатор сообщения');
            return false;
        }
    }

}