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
    public $type;

    public function __construct(array $data) {
        $this->name = $data["Field"];
        $this->type = $data["Type"];
    }

    /**
     * @return bool
     */
    public function isTextField() {
        if ($this->type == "text") {
            return true;
        } elseif (CUtils::strLeft($this->type, "(") == "varchar") {
            return true;
        }
        return false;
    }

    /**
     * @return bool
     */
    public function isNumericField() {
        return !$this->isTextField();
    }
}
