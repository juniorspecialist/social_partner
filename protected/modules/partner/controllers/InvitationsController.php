<?php
/**
 * Created by JetBrains PhpStorm.
 * User: artem
 * Date: 12.06.13
 * Time: 10:40
 * To change this template use File | Settings | File Templates.
 */
class InvitationsController extends BaseUserController{

    public  $defaultAction = 'index';


    /*
     * список отправленных смс сообщений юзером
     */
    public function actionIndex(){

        $this->setPageTitle(Yii::app()->config->getPageTitle('Мои приглашения'));

        $criteria = new CDbCriteria;

        $criteria->compare('partner_id',Yii::app()->user->id);

        $dataProvider = new CActiveDataProvider('Invitations', array(
            'criteria'=>$criteria,
            'pagination'=>array(
                'pageSize'=>100,
            ),
        ));

        $this->render('index', array('dataProvider'=>$dataProvider));
    }

    /*
     * форма отправки приглашения
     */
    public function actionForm(){

        $this->setPageTitle(Yii::app()->config->getPageTitle('Отправить приглашения'));

        $model = new Invitations();
        $model->invitations_text = Yii::app()->config->get('INVITATION.TEMPLATE');

        if(isset($_POST['Invitations'])){

            //отправили запрос на отправку приглашений по смс
            $model->attributes = $_POST['Invitations'];

            if($model->validate()){

                if(!$model->hasErrors())
                {
                    $list_p = explode(PHP_EOL, $model->phoneList);

                    foreach($list_p as $phone){
                        if(!$model->hasErrors()){
                            $check = new Invitations();
                            $check->invitations_text = $model->invitations_text;
                            $check->phone = $phone;
                            $check->status = Invitations::STATUS_CREATE;
                            $check->phoneList = $model->phoneList;
                            if(!$check->validate()){
                                $model->addError('phoneList', 'Не правильно указан список номеров телефонов');
                                 //print_r($check->errors); die();
                            }
                        }
                    }

                    if(!$model->hasErrors()){
                        foreach($list_p as $phone){
                            $check = new Invitations();
                            $check->invitations_text = $model->invitations_text;
                            $check->phone = $phone;
                            $check->status = Invitations::STATUS_CREATE;
                            $check->phoneList = $model->phoneList;
                            $check->save();
                        }

                        Yii::app()->user->setFlash('success', 'Приглашения успешно поставлены в очередь на отправку');

                        $this->refresh();
                    }
                }
            }

        }

        $this->render('form', array('model'=>$model));
    }

}