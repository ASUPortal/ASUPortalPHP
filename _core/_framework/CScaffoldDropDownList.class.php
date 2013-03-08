<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Администратор
 * Date: 28.09.12
 * Time: 16:43
 * To change this template use File | Settings | File Templates.
 */
class CScaffoldDropDownList implements IScaffoldable {
    private $_name;
    private $_model;
    private $_values;
    private $_params;
    public function __construct(CModel $model,
                                $name,
                                array $values,
                                array $params = array()) {

        $this->_model = $model;
        $this->_name = $name;
        $this->_params = $params;
        $this->_values = $values;
    }
    public function display(array $attr = null) {
        CHtml::activeLabel($this->_name, $this->_model);
        CHtml::activeDropDownList($this->_name, $this->_model, $this->_values);
        CHtml::error($this->_name, $this->_model);
    }
}
