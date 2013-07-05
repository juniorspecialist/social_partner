<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
    private $_id;
    private $_status;

    public $is_Admin = true;

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
        $model = Partner::model()->findByAttributes(array('email'=>$this->username));

		if($model===null){

            $this->errorCode=self::ERROR_USERNAME_INVALID;

        }elseif($model->password!==$model->encrypting($this->password)){

            $this->errorCode=self::ERROR_PASSWORD_INVALID;

        }else{

            $this->errorCode=self::ERROR_NONE;

            $this->_id = $model->id;
            $this->_status = $model->status;

            $this->setState('status', $model->status);
            $this->setState('fio', $model->fio);
            $this->setState('role', $model->role);
            $this->setState('id', $model->id);
        }

		return !$this->errorCode;
	}

    /*
     * производим авторизацию юзера на основании кукисов
     */
    public function authenticateOnCookie(){
        // поиск пользователя по почте в БД
        $model = Partner::model()->findByAttributes(array('email'=>$this->username));

        if($model===null){

            //echo 'empty_val_model';

            $this->errorCode=self::ERROR_USERNAME_INVALID;

//        }elseif($model->password!==$model->encrypting($this->password)){
//
//            $this->errorCode=self::ERROR_PASSWORD_INVALID;

        }else{
            $this->errorCode=self::ERROR_NONE;

            $this->_id = $model->id;
            $this->_status = $model->status;

            $this->setState('status', $model->status);
            $this->setState('fio', $model->fio);
            $this->setState('role', $model->role);
            $this->setState('id', $model->id);
        }

        return !$this->errorCode;
    }

    public function authenticateAdmin()
    {
        $model = new Partner();

        if(Yii::app()->config->get('ADMIN.EMAIL')!==$this->username){
            $this->errorCode=self::ERROR_USERNAME_INVALID;
        }elseif(Yii::app()->config->get('ADMIN.PASSWORD')!==$model->encrypting($this->password)){
            $this->errorCode=self::ERROR_PASSWORD_INVALID;
        }else{
            $this->errorCode=self::ERROR_NONE;
            //if($this->is_Admin){
                //echo 'is_admin = true<br>';
                $this->_id = 1;
                $this->setState('role', Partner::ROLE_ADMIN);
                $this->setState('fio', 'Администратор');
                $this->setState('id', 1);
            //}
        }

        //echo 'code_resturn='.$this->errorCode;

        return !$this->errorCode;
    }


    /**
     * @return integer the ID of the user record
     */
    public function getId()
    {
        return $this->_id;
    }

    public function getStatus(){
        return $this->_status;
    }
}