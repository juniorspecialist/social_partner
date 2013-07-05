<?php
/**
 * Created by JetBrains PhpStorm.
 * User: artem
 * Date: 04.06.13
 * Time: 10:00
 * To change this template use File | Settings | File Templates.
 */
?>

<?php
if(Yii::app()->user->hasFlash('error') || Yii::app()->user->hasFlash('success')){


//$this->widget('application.extensions.PNotify.PNotify',array(
//        'options'=>array(
//            'title'=>'You did it!',
//            'text'=>'This notification is awesome! Awesome like you!',
//            'type'=>'success',
//            'closer'=>true,
//            'hide'=>true,
//            'delay'=>3000,
//            'addclass'=>'red',
//        )
//    )
//);

//    Yii::app()->user->setFlash('success',
//        array(
//            'title' => 'Login Successful!',
//            'text' => 'You successfully logged in. Enjoy!',
//        )
//    );
    $this->widget('application.extensions.PNotify.PNotify',
        array(
            'flash_messages_only' => true,
            'options'=>array(
                'closer'=>true,
                'hide'=>true,
                'delay'=>3000,
            )
        )
    );


//    $this->widget('application.extensions.PNotify.PNotify',array(
//            'options'=>array(
//                'title'=>'You did it!',
//                'text'=>'This notification is awesome! Awesome like you!',
//                'type'=>'info',
//                'closer'=>false,
//                'hide'=>true))
//    );
    //echo 'type='.$msg['type'].'|msg='.$msg['msg'];

}
//print_r($msg);
?>

<h2>Личное развитие:</h2>

<strong>ФИО:</strong> <?=$model->fio?><br>

<strong>Мой номер счета:</strong> <?=$model->id;?> <br>

<strong>Статус:</strong> <?=$model->statuspartner;?> <br>

<strong>Уровень в партнерской программе:</strong> <?=$model->partnerlevel?> <br>

<strong>Процент отчислений с взносов сотрудников 2-10 уровней:</strong> <?=$model->bonus_from_other_levels?>% <br>

<? if($parent!==null){ echo '<strong>Id спонсора:</strong> '.$parent->id.'<br>';echo '<strong>ФИО спонсора:</strong> '.$parent->fio.'<br><br><br>';}?>