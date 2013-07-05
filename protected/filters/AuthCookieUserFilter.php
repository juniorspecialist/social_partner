<?php
/**
 * Created by JetBrains PhpStorm.
 * User: artem
 * Date: 05.06.13
 * Time: 12:53
 * To change this template use File | Settings | File Templates.
 */
/*
 * фильтр для принудительной авторизации пользователя, если он авторизовался в соц. сети
 * по кукисам, мы сравниваем его данные с нашими в БД и авторизовываем его вручную
 */
class AuthCookieUserFilter extends  CFilter{

    protected function preFilter($filterChain)
    {

        if(Yii::app()->request->getUserHostAddress()!=='127.0.0.1'){

            // пользователь не авторизован, но есть кукисы
            if(Yii::app()->user->isGuest && isset(Yii::app()->request->cookies['user_email']->value) && isset(Yii::app()->request->cookies['user_id']->value)){

                $cookie_email = (isset(Yii::app()->request->cookies['user_email']->value)) ? Yii::app()->request->cookies['user_email']->value : '';

                $cookie_id = (isset(Yii::app()->request->cookies['user_id']->value)) ? Yii::app()->request->cookies['user_id']->value : '';

                //на основании кукисов - авторизовываем юзера в нашей системе

                $sql = 'SELECT password FROM {{partner}} WHERE email=:email AND id=:id';

                $connect = Yii::app()->db;

                $query = $connect->createCommand($sql);

                $query->bindValue(':email', $cookie_email, PDO::PARAM_STR);
                $query->bindValue(':id', $cookie_id, PDO::PARAM_INT);

                $row = $query->queryRow();

                if(!empty($row)){

                    $_identity = new UserIdentity($cookie_email,$row['password']);

                    $_identity->authenticateOnCookie();

                    if($_identity->errorCode===UserIdentity::ERROR_NONE)
                    {
                        //$duration=$this->rememberMe ? 3600*24*1 : 0; // 30 days
                        $duration = 3600*24*1;
                        Yii::app()->user->login($_identity,$duration);
                    }else{
                        //echo '_identity->errorCode<br>'.$_identity->errorCode;
                    }
                }else{
                    //echo 'emty_row<br>';
                }
            }

            //сравниваем почту из куки и ту, что в сессии
            if(!Yii::app()->user->isGuest && isset(Yii::app()->request->cookies['user_email']->value) && isset(Yii::app()->request->cookies['user_id']->value)){
                // разлогиниваем текущего юзера, и авторизиуем по тому, по какому кукисы записаны
                if(Yii::app()->request->cookies['user_id']->value!==Yii::app()->user->id){

                    Yii::app()->user->logout();

                    $cookie_email = (isset(Yii::app()->request->cookies['user_email']->value)) ? Yii::app()->request->cookies['user_email']->value : '';
                    $cookie_id = (isset(Yii::app()->request->cookies['user_id']->value)) ? Yii::app()->request->cookies['user_id']->value : '';

                    //на основании кукисов - авторизовываем юзера в нашей системе
                    $sql = 'SELECT password FROM {{partner}} WHERE email=:email AND id=:id';

                    $connect = Yii::app()->db;

                    $query = $connect->createCommand($sql);

                    $query->bindValue(':email', $cookie_email, PDO::PARAM_STR);
                    $query->bindValue(':id', $cookie_id, PDO::PARAM_INT);

                    $row = $query->queryRow();

                    if(!empty($row)){

                        $_identity = new UserIdentity($cookie_email,$row['password']);

                        $_identity->authenticateOnCookie();

                        if($_identity->errorCode===UserIdentity::ERROR_NONE)
                        {
                            //$duration=$this->rememberMe ? 3600*24*1 : 0; // 30 days
                            $duration = 3600*24*1;
                            Yii::app()->user->login($_identity,$duration);
                        }else{
                            //echo '_identity->errorCode<br>'.$_identity->errorCode;
                        }
                    }
                }
            }

            if(Yii::app()->user->isGuest){
                Yii::app()->request->redirect(Yii::app()->params['url_social']);
            }


            //проверяй в Yii наличие такой куки. Если нету но человек залогинен в Yii   - кидай на логаут
            if(!Yii::app()->user->isGuest){
                if(!isset(Yii::app()->request->cookies['user_email']->value)){
                    Yii::app()->request->redirect('/site/logout');
                }
            }

        }

        // код, выполняемый до выполнения действия
        return true; // false — для случая, когда действие не должно быть выполнено
    }

    protected function postFilter($filterChain)
    {
        // код, выполняемый после выполнения действия
    }
}