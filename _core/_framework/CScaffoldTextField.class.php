<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Администратор
 * Date: 28.09.12
 * Time: 16:23
 * To change this template use File | Settings | File Templates.
 */
class CScaffoldTextField implements IScaffoldable{
    private $_model = null;
    private $_name = null;
    public function __construct(CModel $model,
                                $name) {

        $this->_model = $model;
        $this->_name = $name;
    }
    public function display(array $attr = null) {
        CHtml::activeLabel($this->_name, $this->_model);
        CHtml::activeTextField($this->_name, $this->_model);
        CHtml::error($this->_name, $this->_model);
    }
}
