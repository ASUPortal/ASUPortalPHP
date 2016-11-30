<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Администратор
 * Date: 06.10.12
 * Time: 18:19
 * To change this template use File | Settings | File Templates.
 */
class CHelp extends CActiveModel {
    protected $_table = TABLE_HELP;
    protected $_aclControlEnabled = true;
    public static function getClassName() {
        return __CLASS__;
    }
    public function attributeLabels() {
        return array(
            "title" => "Название страницы",
            "url" => "Адрес внутри портала",
            "wiki" => "Страница справки в локальной Википедии",
            "content" => "Текст справки"
        );
    }
    public function validationRules() {
        return array(
            "required" => array(
                "title",
                "url",
                "content"
            )
        );
    }
}
