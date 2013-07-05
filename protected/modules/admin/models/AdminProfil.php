<?php
/**
 * Created by JetBrains PhpStorm.
 * User: artem
 * Date: 28.05.13
 * Time: 10:03
 * To change this template use File | Settings | File Templates.
 */

class AdminProfil extends CFormModel {

    public $email;
    public $password;
    public $password_new;
    public $phone1;
    public $phone2;
    public $invitation;

    public function rules()
    {
        return array(
            array('email, phone1, phone2', 'required'),
            array('email', 'email'),
            array('password_new, password','isCorrectPasswords'),
            array('password_new','currentPasswordIsValidate'),
            array('invitation', 'length', 'max'=>256),
//            array('phone1, phone2', 'filter','filter'=>array('MainFilter', 'mobilePhone')),
//            array('phone1, phone2','DPhone'),
        );
    }

    public function attributeLabels()
    {
        return array(
            'password_new'=>'Новый пароль',
            'password'=>'Пароль',
            'phone1'=>'Телефон(1)',
            'phone2'=>'Телефон(2)',
            'email'=>'Почта',
            'invitation'=>'Шаблон отправки приглашения(смс)',
        );
    }

    /*
     * если указали новый пароль, то нужно указывать и старый
     * если указали старый пароль нужно указывать и новый
     */
    public function isCorrectPasswords(){
        if(!$this->hasErrors()){
            //если указали новый пароль, то нужно указывать и старый
            if(!empty($this->password_new) && empty($this->password)){
                $this->addError('password', 'Необходимо указать старый пароль');
            }
            //если указали старый пароль, то нужно указывать и новый пароль
            if(empty($this->password_new) && !empty($this->password)){
                $this->addError('password_new', 'Необходимо указать новый пароль');
            }
        }
    }

    /*
     * проверяем соответствие текущего пароля с ввёденным
     */
    public function currentPasswordIsValidate(){

        if(!$this->hasErrors() && !empty($this->password_new)){
            $model = new Partner();
            if($model->encrypting($this->password)!==Yii::app()->config->get('ADMIN.PASSWORD')){
                $this->addError('password', 'Вы указали не верно текущий ваш пароль');
            }
        }
    }

    public function getEmail(){
        return Yii::app()->config->get('ADMIN.EMAIL');
    }

    public function getPhone1(){
        return Yii::app()->config->get('ADMIN.PHONE1');
    }

    public function getPhone2(){
        return Yii::app()->config->get('ADMIN.PHONE2');
    }

    public function getinvitation(){
        return Yii::app()->config->get('INVITATION.TEMPLATE');

        //return 'asdfasdfasdfasdf';
    }



}