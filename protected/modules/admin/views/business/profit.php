<?php
/**
 * Created by JetBrains PhpStorm.
 * User: artem
 * Date: 08.05.13
 * Time: 14:39
 * To change this template use File | Settings | File Templates.
 */
?>
<h3>Доход</h3>
<strong>Сумарный оборот:</strong> <?=Main::convNumber($data['suma_rev'])?><br>
<strong>Суммарный доход:</strong> <?=Main::convNumber($data['suma_partners_bals']);?>