<h3>Структура пользователя</h3>
<?php
/**
 * Created by JetBrains PhpStorm.
 * User: artem
 * Date: 08.05.13
 * Time: 14:55
 * To change this template use File | Settings | File Templates.
 */
?>

<p><i>Зелёный цвет - статус партнера, красный цвет - статус участника</i></p>

<?php

$this->renderPartial('_structure_filters', array('model'=>$model));

Yii::app()->clientScript->registerScript('search', "
    $('a.filters').on('click' ,function(){
        $('#filter_form_tree').css({
            'top' : event.pageY + 5,
            'left' : event.pageX + 5
        });

        $('#filter_form_tree').show();

        return false;
    });
");

echo CHtml::link('Фильтрация', 'javascript::void(0)', array('class'=>'filters'));

?>
<?
$this->widget('CTreeView',array(
    'id'=>'unit-treeview',
    'url'=>array('tree'),
    //'data'=>$tree,
    'persist'=>'location', // метод запоминания открытого узла
    'unique'=>true, // если тру, то при открытии одного узла, будут закрываться остальные
    'htmlOptions'=>array(
        'class'=>'treeview-black'
    )
));
?>
<div id="tbl_tree"></div>

<!--всплывающая подсказка при клике на ссылку в дереве-->
<div id="tooltip" style="display: none"></div>

<div class="go-up" title="Вверх" id='ToTop'>⇧</div>
<div class="go-down" title="Вниз" id='OnBottom'>⇩</div>

<script type="text/javascript">

    $(document).ready(function() {

        $('#filter_form_tree_forms').submit(function(){

            $.ajax({
                //url: '/partner/business/ajaxinfo',             // указываем URL и
                //type: 'POST',
                data: $(this).serialize(),
                //dataType : "json",                     // тип загружаемых данных
                success: function (data, textStatus) { // вешаем свой обработчик на функцию success
                    $('#unit-treeview').hide();
                    $('#tbl_tree').html(data);
                }
            });

            return false;
        });

        $('.row-tree').live("click", function(event){

            // если данные уже отправлялись по данной ссылке, то выводим их, повторно не отправляем
            $data_tooltip = $(this).attr("data");

            $.ajax({
                url: '/partner/business/ajaxinfo',             // указываем URL и
                type: 'POST',
                data:$data_tooltip,
                //dataType : "json",                     // тип загружаемых данных
                success: function (data, textStatus) { // вешаем свой обработчик на функцию success

                    $('#'+event.target.id).attr('data_tooltip',data);

                    $("#tooltip").html(data);

                    $("#tooltip").css({
                        "top" : event.pageY + 5,
                        "left" : event.pageX + 5
                    });
                    $("#tooltip").show();
                }
            });

            return false;
        })

        // прячем всплывающее окно сообщений
        $(document).on("click", "",function () {

            // hide form filters of partners
            if(event.target.id=='close_filters'){
                // hide form filters
                $('#filter_form_tree').hide();
            }

            // show partner-tree
            if(event.target.id=='cancel_filters'){

                $('#filter_form_tree').hide();

                $('#unit-treeview').show();

                $('#tbl_tree').hide();
            }

            $("#tooltip").hide()
                .text("")
                .css({
                    "top" : 0,
                    "left" : 0
                });

            var id_object = event.target.id;

            if(id_object!=='OnBottom' && id_object!=='ToTop'){
                arrow();
            }
        });

        /* стрелки прокрутки вверх и вниз*/
        function arrow(){
            if (jQuery(window).scrollTop()>='250'){
                jQuery('#ToTop').fadeIn('fast');
            }
            jQuery(window).scroll(function(){
                if (jQuery(window).scrollTop()<='250'){
                    jQuery('#ToTop').fadeOut('fast');
                }else{
                    jQuery('#ToTop').fadeIn('fast')
                }
            });

            if (jQuery(window).scrollTop()<=jQuery(document).height()-'999') jQuery('#OnBottom').fadeIn('fast');
            jQuery(window).scroll(function(){
                if (jQuery(window).scrollTop()>=jQuery(document).height()-'999') jQuery('#OnBottom').fadeOut('fast');
                else jQuery('#OnBottom').fadeIn('fast');
            });

            jQuery('#ToTop').on('click', function(){jQuery('html,body').animate({scrollTop:0},'fast')});

            jQuery('#OnBottom').on('click' ,function(){
                jQuery('html,body').animate({scrollTop:jQuery(document).height()},'fast');
            });

            return false;
        }

    });// Ready end.
</script>