<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 08.03.13
 * Time: 20:47
 * To change this template use File | Settings | File Templates.
 */
class CDbTableField {
    public $name;

    public function __construct(array $data) {
        $this->name = $data["Field"];
    }
}
