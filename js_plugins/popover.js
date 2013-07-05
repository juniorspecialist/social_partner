/**
 * Created with JetBrains PhpStorm.
 * User: artem
 * Date: 10.05.13
 * Time: 14:20
 * To change this template use File | Settings | File Templates.
 */
/*
всплывающая подсказка при наведении курсора на ссылку в дереве партнёров, отображаем данные по партнёру
 */
$("[data-tooltip]").mousemove(function (eventObject) {

    $data_tooltip = $(this).attr("data-tooltip");

    $("#tooltip").text($data_tooltip)
        .css({
            "top" : eventObject.pageY + 5,
            "left" : eventObject.pageX + 5
        })
        .show();

}).mouseout(function () {

        $("#tooltip").hide()
            .text("")
            .css({
                "top" : 0,
                "left" : 0
            });
    });
