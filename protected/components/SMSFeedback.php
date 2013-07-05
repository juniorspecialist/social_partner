<?php
/**
 * Created by JetBrains PhpStorm.
 * User: artem
 * Date: 06.05.13
 * Time: 17:26
 * To change this template use File | Settings | File Templates.
 */

class SMSFeedback{

    //amazing1
    protected $login;//логин доступа к СМС сервису http://www.smsfeedback.ru/

    //zx78op01
    protected $pass;//пароль доступа к СМС сервису http://www.smsfeedback.ru/

    public $error = false;//флаг наличия ошибок
    public $error_desc;// текстовое описание ошибки
    public $url;//адрес запроса, для отправки и получения данных от смс-сервиса
    public $balance;// баланс пользователя
    public $valute;// валюта пользователя

    public $host;// хост сайта, АПИ котор. мы будем использовать
    public $port;// какой порт исползовать при отправке GET запроса через сокеты
    public $phone;// номер телефона, куда отправляем СМС
    public $sender = false;
    public $text;//текст сообщения
    public $wapurl = false;

    /*
     * проверяем необходмсые параметры , на указание - валидация
     */
    public function checkParams(){

        $this->login = 'amazing1';
        $this->pass = 'zx78op01';

        if(empty($this->login)){ $this->error = true; $this->error_desc.='Не указан логин к смс-сервису.'; }

        if(empty($this->pass)){ $this->error = true; $this->error_desc.='Не указан пароль к смс-сервису.'; }

        if(empty($this->text)){ $this->error = true; $this->error_desc.='Не указан текст сообщения.'; }


        // проверим длину сообщения
        if(!$this->error){

            if(strlen($this->text)>70){
                $this->error = true;
                $this->error_desc.='ДЛина смс превышает 70 символов';
            }
        }

        //если не было ошибок, проверка прошла успешно, формируем адрес для отправки запроса
        if(!$this->error){
            $this->url = 'http://'.$this->login.':'.$this->pass.'@api.smsfeedback.ru/m$responseBodyessages/v2/';
        }
    }

    /*
     * информация о текущем балансе пользователя
     * Проверка состояния счета
     */
    public function getBalance(){

        //params validation
        $this->checkParams();

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

    public function sendSms(){
        $fp = fsockopen($this->host, $this->port, $errno, $errstr);
        if (!$fp) {
            return "errno: $errno \nerrstr: $errstr\n";
        }
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

        // проверим результат отправки запроса
        $this->checkResponse($responseBody);
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
                $this->error_desc.='Неверно задан номер телефона (формат 71234567890).';
            }
            if($expl[0]=='text is empty'){
                $this->error_desc.='Отсутствует текст.';
            }
            if($expl[0]=='text must be string'){
                $this->error_desc.='Текст не на латинице или не в utf-8 .';
            }
            if($expl[0]=='sender address invalid'){
                $this->error_desc.='Неверная (незарегистрированная) подпись отправителя.';
            }
            if($expl[0]=='wapurl invalid'){
                $this->error_desc.='Неправильный формат wap-push ссылки.';
            }
            if($expl[0]=='invalid schedule time format'){
                $this->error_desc.='Неверный формат даты отложенной отправки сообщения.';
            }
            if($expl[0]=='invalid status queue name'){
                $this->error_desc.='Неверное название очереди статусов сообщений.';
            }
            if($expl[0]=='not enough balance'){
                $this->error_desc.='Баланс пуст (проверьте баланс).';
            }


            if(empty($this->error_desc)){ $this->error_desc.='undefined error.'; }

        }else{

            // возвращаем ID успешно отправленного сообщения через СМС
            return $expl[1];
        }
    }

    /*
    * функция передачи сообщения
     ** использование функции передачи сообщения
        echo send("api.smsfeedback.ru", 80, "login", "password","79031234567", "text here", "TEST-SMS");
    */

    public function send($host, $port, $login, $password, $phone, $text, $sender = false, $wapurl = false )
    {
        $fp = fsockopen($host, $port, $errno, $errstr);
        if (!$fp) {
            return "errno: $errno \nerrstr: $errstr\n";
        }
        fwrite($fp, "GET /messages/v2/send/" .
            "?phone=" . rawurlencode($phone) .
            "&text=" . rawurlencode($text) .
            ($sender ? "&sender=" . rawurlencode($sender) : "") .
            ($wapurl ? "&wapurl=" . rawurlencode($wapurl) : "") .
            "  HTTP/1.0\n");
        fwrite($fp, "Host: " . $host . "\r\n");
        if ($login != "") {
            fwrite($fp, "Authorization: Basic " .
                base64_encode($login. ":" . $password) . "\n");
        }
        fwrite($fp, "\n");
        $response = "";
        while(!feof($fp)) {
            $response .= fread($fp, 1);
        }
        fclose($fp);
        list($other, $responseBody) = explode("\r\n\r\n", $response, 2);
        return $responseBody;
    }
}