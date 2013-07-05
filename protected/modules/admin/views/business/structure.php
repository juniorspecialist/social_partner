<h3>Структура пользователя</h3>
<?php
/**
 * Created by JetBrains PhpStorm.
 * User: artem
 * Date: 08.05.13
 * Time: 14:55
 * To change this template use File | Settings | File Templates.
 */
$this->setPageTitle(Yii::app()->config->getPageTitle('Структура системы'));

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
<p><i>Зелёный цвет - статус партнера, красный цвет - статус участника</i></p>

<div id="tooltip" style="display: none"></div>


<script type="text/javascript">

    $(document).ready(function() {

        $(document).on("click", '.row-tree', function(event){
            // если данные уже отправлялись по данной ссылке, то выводим их, повторно не отправляем
            $data_tooltip = $(this).attr("data");

            /*
            var hint = $('#'+event.target.id).attr("data_tooltip");

            if(hint) {

                $("#tooltip").html(hint);

                $("#tooltip").css({
                    "top" : event.pageY + 5,
                    "left" : event.pageX + 5
                });
                $("#tooltip").show();
            }else{*/
                $.ajax({
                    url: '/admin/business/ajaxinfo',             // указываем URL и
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
            /*}*/
            return false;

        })
        // прячем всплывающее окно сообщений
        $(document).on("click", "",function () {

                $("#tooltip").hide()
                    .text("")
                    .css({
                        "top" : 0,
                        "left" : 0
                    });
            });
    });// Ready end.
</script>