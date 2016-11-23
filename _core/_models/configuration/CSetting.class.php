<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 03.01.13
 * Time: 16:03
 * To change this template use File | Settings | File Templates.
 */
class CSetting extends CActiveModel {
    protected $_table = TABLE_SETTINGS;
    public static function getClassName() {
        return __CLASS__;
    }
    public function attributeLabels() {
        return array(
            "title" => "Название",
            "alias" => "Ключ",
            "value" => "Значение",
            "type" => "Тип",
            "params" => "Код для получения списка подстановки",
            "description" => "Описание"
        );
    }
    public function validationRules() {
        return array(
            "required" => array(
                "title",
                "alias",
                "value"
            )
        );
    }

    /**
     * Получить значение настройки
     *
     * @return mixed|null
     */
    public function getValue() {
        if ($this->type == 1 or $this->solr != 0) {
            return $this->value;
        } elseif ($this->type == 2) {
            eval('$res = '.$this->value.";");
            return $res;
        }
    }
    
    /**
     * Получить псевдоним настройки
     *
     * @return mixed|null
     */
    public function getAlias() {
        return $this->alias;
    }
    
    /**
     * Список настроек коллекции Solr
     *
     * @return CArrayList
     */
    public function getSearchSettingsList() {
        $settings = new CArrayList();
        foreach (CActiveRecordProvider::getWithCondition(TABLE_SETTINGS, "solr=".$this->getId(), "title asc")->getItems() as $ar) {
            $setting = new CSetting($ar);
            $settings->add($setting->getId(), $setting);
        }
        return $settings;
    }
}
