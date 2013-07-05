<?php

class SiteController extends BaseController
{

    public $layout = '//layouts/column2';
    public $defaultAction = '';

//    public function filters()
//    {
//        return array(
//
//            //'postOnly + regpartner', // we only allow deletion via POST request
//
////            array(
////                'application.filters.AuthCookieUserFilter',
////                //'unit'=>'second',
////            ),
//
//            //'accessControl', // perform access control for CRUD operations
//        );
//    }

    public function accessRules()
    {
        return array(

            // разрешаем доступ к ошибке , + к регистрации юзеров =всем юзерам
            array('allow',  // allow all users to perform 'index' and 'view' actions
                'actions'=>array('error', 'login'),
                'users'=>array('*'),
            ),

            // ограничим доступ к регистарции юзеров, только с нашего сервака
            array('allow',
                'actions'=>array('regpartner'),
                'ips'=>array(Yii::app()->request->getUserHostAddress()),
                //'users'=>array('*'),
                //'expression' => 'isset(Yii::app()->user->role) && (Yii::app()->user->role=='.Partner::ROLE_USER.')',
                //'deniedCallback'=>Yii::app()->request->redirect(Yii::app()->params['url_social']),
            ),
//
//            // доступные для рег. юзеров действия
//            array('allow',  // allow all users to perform 'index' and 'view' actions
//                'actions'=>array('index','view'),
//                //'users'=>array('*'),
//                'expression' => 'isset(Yii::app()->user->role) && (Yii::app()->user->role=='.Partner::ROLE_USER.')',
//            ),
            array('deny',  // deny all users
                'users'=>array('*'),
            ),
        );
    }


	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
        $this->layout = '//layouts/column1';

        $this->setPageTitle(Yii::app()->config->getPageTitle('Ошибка:'));

		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
        if(!Yii::app()->user->isGuest){
            if(Yii::app()->user->role==Partner::ROLE_USER){
                $this->redirect($this->createUrl('partner/business/personal'));
            }
            if(Yii::app()->user->role==Partner::ROLE_ADMIN){
                $this->redirect($this->createUrl('admin/profil/update'));
            }
        }

        $this->setPageTitle(Yii::app()->config->getPageTitle('Авторизация пользователя'));

		$model = new LoginPartner();

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['LoginPartner']))
		{
			$model->attributes=$_POST['LoginPartner'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login()){//
                //$this->redirect($this->createUrl('partner/business/personal'));
                Yii::app()->user->setFlash('error','Ошибка! На вашем счете не хватает баллов для покупки комплекта');
                $this->refresh();
            }

		}
		// display the login form
		$this->render('login',array('model'=>$model));
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		//$this->redirect(Yii::app()->homeUrl);
        Yii::app()->request->redirect(Yii::app()->params['url_social']);
	}

    /*
     * контроллер для регистрации пользователя
     */
    public function actionRegPartner(){

        $partner = new Partner();

        $partner->scenario = 'insert';

        $partner->attributes = $_POST;

        if($partner->validate()){

            $partner->partner_soc_id = $partner->id;

            echo '<pre>'; print_r($_POST);

            if(!empty($partner->partner_id)){
                $root = Partner::model()->findByPk($partner->partner_id);
            }else{
                $root = Partner::model()->findByPk(1);
            }

            if($root===null){
                $root = Partner::model()->findByPk(1);
            }


            $partner->appendTo($root);

            echo 'ok';

        }else{
            echo 'error:'; print_r($partner->errors);
        }
    }
}