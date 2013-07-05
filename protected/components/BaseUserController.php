<?php
/**
 * Created by JetBrains PhpStorm.
 * User: artem
 * Date: 03.05.13
 * Time: 10:03
 * To change this template use File | Settings | File Templates.
 */
// контроллер доступа пользователей, ограничиваем доступ только пользователям
class BaseUserController extends BaseController{


    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(

            //'postOnly + delete', // we only allow deletion via POST request
            array(
                'application.filters.AuthCookieUserFilter',
                //'unit'=>'second',
            ),

            'accessControl', // perform access control for CRUD operations
        );
    }

    public function accessRules()
    {
        return array(
            array('allow',  // allow all users to perform 'index' and 'view' actions
                //'actions'=>array('index','view'),
                //'users'=>array('*'),
                'expression' => 'isset(Yii::app()->user->role) && (Yii::app()->user->role=='.Partner::ROLE_USER.')',
            ),
            array('deny',  // deny all users
                'users'=>array('*'),
            ),
        );
    }
}