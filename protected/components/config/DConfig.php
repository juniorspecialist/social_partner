<?php
/**
 * Created by JetBrains PhpStorm.
 * User: artem
 * Date: 24.05.13
 * Time: 14:49
 * To change this template use File | Settings | File Templates.
 */

class DConfig extends CApplicationComponent
{
    public $cache = 0;
    public $dependency = null;

    protected $data = array();

    public function init()
    {
        $db = $this->getDbConnection();

        $items = $db->createCommand('SELECT * FROM {{config}}')->queryAll();

        foreach ($items as $item)
        {
            if ($item['param'])
                $this->data[$item['param']] = $item['value'] === '' ?  $item['default'] : $item['value'];
        }

        parent::init();
    }

    public function get($key)
    {
        if (isset($this->data[$key]))
            return $this->data[$key];
        else
            throw new CException('Undefined parameter ' . $key);
    }

    public function set($key, $value)
    {
        $model = Config::model()->findByAttributes(array('param'=>$key));
        if (!$model)
            throw new CException('Undefined parameter ' . $key);

        $model->value = $value;

        if ($model->save())
            $this->data[$key] = $value;

    }

    public function add($params)
    {

        if (is_array($params))
        {
            //var_dump($params);
            //foreach ($params as $item){
                //echo 'write='; echo '<pre>'; print_r($item);

                $this->createParameter($params);
            //}
        }
        elseif ($params){
            //echo 'add_params<br>';
            //$this->createParameter($params);
        }
    }

    public function delete($key)
    {
        if (is_array($key))
        {
            foreach ($key as $item)
                $this->removeParameter($item);
        }
        elseif ($key)
            $this->removeParameter($key);
    }

    protected function getDbConnection()
    {
        if ($this->cache)
            $db = Yii::app()->db->cache($this->cache, $this->dependency);
        else
            $db = Yii::app()->db;

        return $db;
    }

    protected function createParameter($param)
    {

        if (!empty($param['param']))
        {

            $model = Config::model()->findByAttributes(array('param' => $param['param']));
            if ($model === null)
                $model = new Config();

            $model->param = $param['param'];
            $model->label = isset($param['label']) ? $param['label'] : $param['param'];
            $model->value = isset($param['value']) ? $param['value'] : '';
            $model->default = isset($param['default']) ? $param['default'] : '';
            $model->type = isset($param['type']) ? $param['type'] : 'string';

            if($model->value){
                $model->save();
            }
        }
    }

    protected function removeParameter($key)
    {
        if (!empty($key))
        {
            $model = Config::model()->findByAttributes(array('param'=>$key));
            if ($model)
                $model->delete();
        }
    }

    /*
     * формируем заголовок TITLE страницы, для отображения пользователю
     */
    public function getPageTitle($title){
        if(!Yii::app()->user->isGuest){
            return Yii::app()->user->fio.' - '.$title;
        }else{
            return $title;
        }
    }
}