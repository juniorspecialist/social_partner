<?php

/**
 * ContactForm class.
 * ContactForm is the data structure for keeping
 * contact form data. It is used by the 'contact' action of 'SiteController'.
 */
class ContactForm extends CFormModel
{
	public $name;
	public $email;
	public $subject;
	public $body;
	public $verifyCode;
    public $phone;

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
        //Yii::import('application.components.DPhone');
		return array(
			// name, email, subject and body are required
			array('name, email, subject, body, phone', 'required'),
			// email has to be a valid email address
			array('email', 'email'),

            array('phone', 'filter','filter'=>array('MainFilter', 'mobilePhone')),
            array('phone','DPhone'),
			// verifyCode needs to be entered correctly
			//array('verifyCode', 'captcha', 'allowEmpty'=>!CCaptcha::checkRequirements()),
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
			'verifyCode'=>'Verification Code',
            'phone'=>'Телефон',
		);
	}

}