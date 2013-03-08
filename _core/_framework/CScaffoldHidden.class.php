<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Администратор
 * Date: 28.09.12
 * Time: 16:09
 * To change this template use File | Settings | File Templates.
 */
class CScaffoldHidden implements IScaffoldable {
    private $_model;
    private $_name;
    private $_value;
    public function __construct(CModel $model,
                                $name,
                                $value = "") {

        $this->_model = $model;
        $this->_name = $name;
        $this->_value = $value;
    }
    public function display(array $attr = null) {
        if ($this->_value != "") {
            CHtml::hiddenField($this->_name, $this->_value);
        } else {
            CHtml::activeHiddenField($this->_name, $this->_model);
        }
    }
}
