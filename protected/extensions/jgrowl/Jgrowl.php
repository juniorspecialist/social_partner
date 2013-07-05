<?php
/**
 * Created by JetBrains PhpStorm.
 * User: artem
 * Date: 23.05.13
 * Time: 15:08
 * To change this template use File | Settings | File Templates.
 */
/*https://github.com/stanlemon/jGrowl
 * пример использования скрипта:
 *
     // Sample 1
    $.jGrowl("Hello world!");
    // Sample 2
    $.jGrowl("Stick this!", { sticky: true });
    // Sample 3
    $.jGrowl("A message with a header", { header: 'Important' });
    // Sample 4
    $.jGrowl("A message that will live a little longer.", { life: 10000 });
    // Sample 5
    $.jGrowl("A message with a beforeOpen callback and a different opening animation.", {
        beforeClose: function(e,m) {
            alert('About to close this notification!');
        },
        animateOpen: {
            height: 'show'
        }
    });
 */
/*
 * $message – это сообщение которое будет показываться пользователю
$position – позиция отображаемого окна, по умолчанию center
 */
/*
 * использование:
 * $this->widget('ext.jgrowl.Jgrowl',array('message'=>Yii::app()->user->getFlash('info')));
 */

class Jgrowl extends CWidget
{

    public $message;
    public $position = 'center';

    public function init()
    {

        $script = '
        $(document).ready(function(){

            $.jGrowl(" '.$this->message.' ", {position:" '.$this->position.' "});

        })
        ';
        //,{position : '".$this->position."'

        //$js = Yii::app()->assetManager->publish(dirname(__FILE__) . DIRECTORY_SEPARATOR . '/jquery.jgrowl_compressed.js');

        //$cs = Yii::app()->assetManager->publish(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'jquery.jgrowl.css');


        // подключаем скрипты всплывающих подсказок
        $this->publishJS();

        Yii::app()->clientScript->registerScript('jgrowlWidget', $script);
            //->registerScriptFile($js)
            //->registerCssFile($cs)

    }

    public function publishJS(){

        Yii::app()->clientScript->registerCoreScript('jquery');

        $url = Yii::app()->getAssetManager()->publish( Yii::getPathOfAlias('ext.jgrowl.assets') );

        Yii::app()->clientScript->registerScriptFile($url.'/jquery.jgrowl.js',CClientScript::POS_BEGIN);

        Yii::app()->clientScript->registerCssFile($url.'/jquery.jgrowl.css');
    }

    public function run()
    {

    }

}
?>