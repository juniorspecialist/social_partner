<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
?>

<h1>Welcome to <i><?php echo CHtml::encode(Yii::app()->name); ?></i></h1>

<?php
$this->widget('application.widgets.JsTreeWidget',
    array('modelClassName' => 'Partner',
        'jstree_container_ID' => 'Partner-wrapper',
        'themes' => array('theme' => 'apple', 'dots' => true, 'icons' => true),
        'plugins' => array('themes', 'html_data', 'ui')// 'contextmenu', 'crrm', 'dnd', 'cookies',
    ));
?>