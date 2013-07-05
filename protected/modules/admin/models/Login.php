<?php
/**
 * Created by JetBrains PhpStorm.
 * User: artem
 * Date: 24.05.13
 * Time: 11:57
 * To change this template use File | Settings | File Templates.
 */

/*
 * хитрожопая авторизация админа
 */
class Login extends CFormModel{

    public $username;
    public $password;
    public $smsCode;

    private $_identity;

    public function rules()
    {
        return array(
            // username and password are required
            array('username, password', 'required','on'=>'login'),
            array('username','email','on'=>'login'),
            array('password', 'authenticate','on'=>'login'),

            // проверяем отправленный смс-код на телефон
            array('username, password,smsCode', 'required','on'=>'sms'),
            array('smsCode','validateSms', 'on'=>'sms'),
            array('username, password', 'length','min'=>1,'on'=>'sms'),
        );
    }

    public function validateSms(){
        $model = new Partner();
        if($model->isValidateSmsCode($this->smsCode)){
            return true;
        }else{
            $this->addError('smsCode', 'Код смс указан не верно');
            return false;
        }
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels()
    {
        return array(
            'rememberMe'=>'Remember me next time',
            'username'=>'Почта',
            'password'=>'Пароль',
            'smsCode'=>'код смс',
        );
    }

    /**
     * Authenticates the password.
     * This is the 'authenticate' validator as declared in rules().
     */
    public function authenticate($attribute,$params)
    {
        if(!$this->hasErrors())
        {
            $this->_identity = new UserIdentity($this->username,$this->password);

            $this->_identity->is_Admin = false;

            if(!$this->_identity->authenticateAdmin()){
                $this->addError('password','Не правильно указан логин или пароль.');
            }
        }
    }

    /**
     * Logs in the user using the given username and password in the model.
     * @return boolean whether login is successful
     */
    public function login()
    {
        //echo 'login<br>';

        //if($this->_identity===null)
        //{
            $this->_identity=new UserIdentity($this->username,$this->password);
            $this->_identity->is_Admin = true;
            $this->_identity->authenticateAdmin();
        //}
        if($this->_identity->errorCode===UserIdentity::ERROR_NONE)
        {
            //$duration=$this->rememberMe ? 3600*24*30 : 0; // 30 days
            $duration = 0;
            Yii::app()->user->login($this->_identity,$duration);
            return true;
        }else{

            //echo 'error_code_login:'.$this->_identity->errorCode.'<br>';

            return false;
        }
    }


    // описываем событие onAdminAuth
    public function onAdminAuth($event) {
        // Непосредственно вызывать событие принято в его описании.
        // Это позволяет использовать данный метод вместо raiseEvent
        // во всём остальном коде.
        $this->raiseEvent('onAdminAuth', $event);
    }
}