<?php
/**
 * Created by JetBrains PhpStorm.
 * User: artem
 * Date: 28.05.13
 * Time: 12:28
 * To change this template use File | Settings | File Templates.
 */

/*
 * модель для изменения спонсора у выбранного юзера в дереве
 */
class ChangeSponsor extends CFormModel {

    public $partner;
    public $sponsor_new;

    public $_modelPartner;
    public $_modelSponsor;

    public function rules()
    {
        return array(
            array('partner, sponsor_new', 'required'),
            //array('partner, sponsor_new', 'email'),
            array('partner, sponsor_new', 'numerical', 'integerOnly'=>true),
            array('partner, sponsor_new','issetPartnerSponsor'),
            // проверки доступности изменения родителя у реферала
            array('partner','checkPartner'),
        );
    }

    /*
     * проверяем существуют ли в БД указанные партнёры по указанной почте
     */
    public function issetPartnerSponsor(){
        if(!$this->hasErrors()){
            $partner = Partner::model()->findByPk($this->partner);
            if($partner===null){
                $this->addError('partner', 'Не найден партнёр по указанному номеру счета');
            }else{
                $this->_modelPartner = $partner;
            }
        }
        if(!$this->hasErrors()){
            $sponsor = Partner::model()->findByPk($this->sponsor_new);
            if($sponsor===null){
                $this->addError('sponsor_new', 'Не найден новый партнёр по указанному номеру счета');
            }else{
                $this->_modelSponsor = $sponsor;
            }
        }
    }

    // у выбранного партнёра, которму изменим спонсора, не должно быть рефералов
    //+ не должно быть куплено партнёрского комплекта+ не должно быть фин. операций, кроме пополнения баланса
    public function checkPartner(){

        if(!$this->hasErrors()){

            // если юзер уже купил партнёрский комплект - ошибка
            if($this->_modelPartner->status==Partner::STATUS_Partner){
                $this->addError('partner',"Выбранный партнёр имеет партнёрский статус, изменение спонсора не возможно");
            }

            //если у юзера уже есть рефералы, тогда - ошибка
            $childrens = $this->_modelPartner->countChildren();

            if($childrens>0){
                $this->addError('partner', 'Выбранный партнёр имеет рефералов нижних уровней, смена спонсора не возможна');
            }
        }
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels()
    {
        return array(
            'partner'=>'Партнер',
            'sponsor_new'=>'Новый спонсор',
        );
    }}