<?php
/**
 * Created by JetBrains PhpStorm.
 * User: artem
 * Date: 29.05.13
 * Time: 16:36
 * To change this template use File | Settings | File Templates.
 */

class InputCashMoney extends  CFormModel{

    public $sum;
    public $partner_id;

    /**
     * Declares the validation rules.
     */
    public function rules()
    {
        return array(
            // name, email, subject and body are required
            array('sum, partner_id', 'required'),
            array('sum', 'numerical', 'integerOnly'=>true),
        );
    }

    /**
     * Declares customized attribute labels.
     * If not declared here, an attribute would have a label that is
     * the same as its name with the first letter in upper case.
     */
    public function attributeLabels()
    {
        return array(
            'sum'=>'Сумма',
            'partner_id'=>'ID',
        );
    }
}