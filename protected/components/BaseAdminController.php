<?php
/**
 * Created by JetBrains PhpStorm.
 * User: artem
 * Date: 03.05.13
 * Time: 10:11
 * To change this template use File | Settings | File Templates.
 */
// контроллер доступа для админа, разрешён доступ только у админа
class BaseAdminController extends BaseController{

    public $layout = '//layouts/column1';


    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
            //'postOnly + delete', // we only allow deletion via POST request
        );
    }

    public function accessRules()
    {
        return array(
            array('allow',  // allow all users to perform 'index' and 'view' actions
                'actions'=>array('login'),
                'users'=>array('*'),
                //'expression' => 'isset(Yii::app()->user->role) && (Yii::app()->user->role==='.Partner::ROLE_ADMIN.')',
            ),
            array('allow',  // allow all users to perform 'index' and 'view' actions
                //'actions'=>array('index','view'),
                //'users'=>array('*'),
                'expression' => 'isset(Yii::app()->user->role) && (Yii::app()->user->role=='.Partner::ROLE_ADMIN.')',
            ),
            array('deny',  // deny all users
                'users'=>array('*'),
            ),
        );
    }

    public function init()
    {
//        Yii::app()->themeManager->basePath .= '/admin';
//        Yii::app()->themeManager->baseUrl .= '/admin';

        Yii::app()->theme = 'default'; // You can set it there or in config or somewhere else before calling render() method.
    }
}