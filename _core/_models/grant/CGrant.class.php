<?php
/**
 * Created by JetBrains PhpStorm.
 * User: User
 * Date: 01.04.13
 * Time: 9:53
 * To change this template use File | Settings | File Templates.
 */

class CGrant extends CActiveModel{
    protected $_table = TABLE_GRANTS;
    public function attributeLabels() {
        return array(
            "title" => "Название",
            "comment" => "Комментарий"
        );
    }
}