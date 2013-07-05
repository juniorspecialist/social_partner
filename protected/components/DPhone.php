<?php
/**
 * Created by JetBrains PhpStorm.
 * User: artem
 * Date: 23.05.13
 * Time: 12:42
 * To change this template use File | Settings | File Templates.
 */

class DPhone extends CValidator {
    /* @var int $minNumDigits minimum allowed number of digits in the phone number */
    public $minNumDigits = 10;

    /* @var int $minNumDigits maximum allowed number of digits in the phone number */
    public $maxNumDigits = 15;

    /* @var bool $allowEmpty Whether the attribute is allowed to be empty. */
    public $allowEmpty = false;
    /* @var string $message default error message.
     * Note that if you wish it to be translated please pass translated value to this validator class in rules() method
     * for the relevant AR class. */
    public $message = "Номер телефона заполнен не верно";

    /* @var string $emptyMessage the message to be displayed if an empty value is validated while 'allowEmpty' is false */
    public $emptyMessage = "{attribute} значение не может быть пустым";
    /* @var bool $logValidationErrors whether to log validation errors or not. When logging is enabled, the log message
     *     includes the invalid value. I wasn't sure about possible security implications of this so this is by default false. */
    public $logValidationErrors = false;

    private $codeCountry;// код страны для мобильного

    /**
     * validates $attribute in $object.
     *
     * @param CModel $object the object to check
     * @param string $attribute the attribute name to validate in the given $object.
     *
     * @throws CException
     */
    protected function validateAttribute($object, $attribute) {
        // если 'allowEmpty' is true and the attribute is indeed empty - всё отлично, закончили валидацию
        if (empty($object->$attribute)) {
            if ($this->allowEmpty) {
                return true;
            }
            $translated_msg = Yii::t("DPhone.general", $this->emptyMessage, array('{attribute}' => $object->$attribute));
            $this->addError($object, $attribute, $translated_msg);
            return true;
        }

        // запускаем список проверок по мобильному на валидность
        $isValidatePhone = $this->runValidateProne($object, $attribute);

        // проверки прошли успешно - номер валидный
        if($isValidatePhone){
            return true;
        }

        // не валидный номер
        if ($this->logValidationErrors) {
            Yii::log("phone number in object of type " . get_class($object) . ", as checked in attribute named $attribute, was found to be invalid." .
            " Value supplied = " . $object->$attribute, CLogger::LEVEL_INFO, __METHOD__);
        }
    }

    /*
     * валидация номера телефона по его длине
     */
    public function isValidateLenPhone($object, $attribute){
        /*
         * strip down anything that is not a digit.
         * at the end, we should be left with number of digits that is no less than minNumDigits and no
         * more than maxNumDigits.
         */
        $stripped = mb_ereg_replace('\D', "", $object->$attribute);

        if ((strlen($stripped) > $this->minNumDigits) && (strlen($stripped) < $this->maxNumDigits)) {
            // валидный номер
            return true;
        }

        $this->addError($object, $attribute, 'Не верный формат номера, например - необходимо указать номер в формате 79210000000');

        return false;
    }

    /*
     * последовательная валидация номера телефона
     * 1 - по коду страну, 2- по доступному списку кодов мобильных операторов в зависимости от выбранной страны
     */
    public function runValidateProne($object, $attribute){

        // сперва проверка по длине номера телефона
        $validateLenPhone = $this->isValidateLenPhone($object, $attribute);
        if(!$validateLenPhone){
            return false;
        }

        // 1 - по коду страну,
        $validateCountryCode = $this->isValidateCountryCode($object, $attribute);
        if(!$validateCountryCode){
            return false;
        }

        //2- по доступному списку кодов мобильных операторов в зависимости от выбранной страны
        $validateMobileCode = $this->isValidateCodeMobileOperator($object, $attribute);
        if(!$validateMobileCode){
            return false;
        }

        return true;
    }

    /*
     получаем в номере код страны и валидируем с имеющимися кодами стран
     */
    public function isValidateCountryCode($object, $attribute){

        // получаем список кодов стран(доступных для валидации)
        $list_code = $this->listCodeCountry();

        foreach($list_code as $country_code){

            // нашли соответствие кода в списке
            if(preg_match('/^'.$country_code.'(.*)/i', $object->$attribute)) {

                $this->codeCountry = $country_code;

                return true;
            }
        }

        $this->addError($object, $attribute, 'Код страны в номере мобильного, не поддерживается нашей системой отправки СМС');

        return false;
        //$string = strpos("Hello, world!", "world");

        //echo($string);
    }

    /*
     * после валидации кода страны
     * валидируем код мобильного оператора данной страны
     */
    public function isValidateCodeMobileOperator($object, $attribute){

        // по коду страны определяем список мобильных кодов оператора
        $codeList = $this->getLisCodeMobileOperatorsByCountryCode($this->codeCountry);

        foreach($codeList as $mobileCode){
            // составляем регулярку относительно кода страны и перебираемого кода мобильного оператора
            if(preg_match('/^'.$this->codeCountry.$mobileCode.'/i', $object->$attribute)){
                return true;
            }
        }

        $this->addError($object, $attribute, 'Код мобильного оператора, не поддерживается нашей системой отправки СМС');

        return false;
    }

    /*
     * список кодов стран
     */
    public function listCodeCountry(){
        return array(
            '380','7'
        );
    }

    /*
     * получаем список приставок мобильных операторов по коду страны
     */
    public function getLisCodeMobileOperatorsByCountryCode($code_country){
        //для украины
        if($code_country=='380'){
            return $this->listCodeMobileOperatorsUkr();
        }
        //для россии
        if($code_country=='7'){
            return $this->listCodeMobileOperatorsRus();
        }

        // не найдено списка кодов соответствий для кода страны
        return false;
    }


    /*
     * список кодов мобильных приставок для номеров телефона по стране - Россия
     */
    public function listCodeMobileOperatorsRus(){
        return array(
            '900','901','902','903','904','905','906','908','909','910','911','912','913','914','915','916','917','918','919','920','921','922','923','924','925','926','927','928'
            ,'929','930','930','931','932','933','934','935','936','937','937','950','951','952','953','960','961','962','963','964','965','966','967','968','980','981','982','983','984'
            ,'985','986','987','988','989','997'
        );
    }
    /*
     * список кодов мобильных приставок для номеров телефона по стране - Украине
     */
    public function listCodeMobileOperatorsUkr(){
        return array(
            '39','50','63','66','67','68','91','92','93','94','95','96','97','98','99'
        );
    }

}