<?php
/**
 * Created by JetBrains PhpStorm.
 * User: artem
 * Date: 27.05.13
 * Time: 10:40
 * To change this template use File | Settings | File Templates.
 */
class AUserIdentity extends CUserIdentity
{
    private $_id;
    private $_status;

    public $can_createSession = false;

    /**
     * Authenticates a user.
     * The example implementation makes sure if the username and password
     * are both 'demo'.
     * In practical applications, this should be changed to authenticate
     * against some persistent user identity storage (e.g. database).
     * @return boolean whether authentication succeeds.
     */
    public function authenticate()
    {
        // поиск пользователя по почте в БД
        //$model = Partner::model()->findByAttributes(array('email'=>$this->username));
        $model = new Partner();

        if(Yii::app()->config->get('ADMIN.EMAIL')!=$this->username){
            $this->errorCode=self::ERROR_USERNAME_INVALID;
        }elseif(Yii::app()->config->get('ADMIN.PASSWORD')!==$model->encrypting($this->password)){
            $this->errorCode=self::ERROR_PASSWORD_INVALID;
        }else{
            $this->errorCode=self::ERROR_NONE;

            if($this->can_createSession){
                $this->_id = 1;
                //$this->_status = $model->status;
                //$this->setState('status', $model->status);
                $this->setState('role', Partner::ROLE_ADMIN);
                $this->setState('id', 1);
                echo 'auth<br>';
            }
        }

        return !$this->errorCode;
    }

    /**
     * @return integer the ID of the user record
     */
    public function getId()
    {
        return $this->_id;
    }
}