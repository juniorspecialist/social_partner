<?php
/**
 * Created by JetBrains PhpStorm.
 * User: artem
 * Date: 08.05.13
 * Time: 14:19
 * To change this template use File | Settings | File Templates.
 */
?>
<div id="personal_info">
    <?php
        $this->renderPartial('_personal_info', array('model'=>$model,'parent'=>$parent));
    ?>
</div>

<?
$this->widget('zii.widgets.CListView', array(
    'dataProvider'=>$dataProviderPartnerComplekts,
    'id'=>'partner_komplekts',
    'itemView'=>'_partner_complect',   // refers to the partial view named '_post'
    'viewData'=>array(
        'status'=>$model->status
    ),
    'template'=>'{items}',
    'emptyText'=>'Список партнерских комплектов пуст',
));

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

?>
<script type="text/javascript">
    jQuery(function($) {
        jQuery(document).on('click','.buy_partner_ship',function(e){

            e.preventDefault();

            jQuery.ajax({
                'type':'POST',
                //'datatype':'json',
                'beforeSend':function(request){
                    var link_id = e.target.id;
                    $('#'+link_id).hide();
                    var parent = $('#'+link_id).parent().append('<img src="/images/ajax-loader.gif">');
                },
                'success':function(data){
                    //$('#personal_info').html(data);
                    // обновляем список комплектов, вдруг изменилась цена на комплект для юзера
                    //$.fn.yiiListView.update('partner_komplekts');
                    document.location.href = "";
                },
                'data':$(this).attr('data'),
                'cache':'false',
                'url':'/partner/business/buyPartner'
            });
            return false;
        });
    });
</script>