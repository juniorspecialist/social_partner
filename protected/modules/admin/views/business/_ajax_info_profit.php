<?php
/**
 * Created by JetBrains PhpStorm.
 * User: artem
 * Date: 21.05.13
 * Time: 12:15
 * To change this template use File | Settings | File Templates.
 */
?>

<strong>Внесено средств на оплату партнерских комплектов</strong>: <?=Main::convNumber($data['inComePaymentByPartner'])?><br>

<strong>Суммарная прибыль с пользователя от продажи Партнерских комплектов</strong>: <?=Main::convNumber($data['sumProfitFromUserByPartnerShip'])?><br>

<!--<strong>Всего внесено средств на счет</strong>: --><?//=Main::convNumber($data['AllinputAccountBalance'])?><!--<br>-->

<strong>Текущий остаток на счете</strong>: <?=Main::convNumber($data['balance'])?> <br>