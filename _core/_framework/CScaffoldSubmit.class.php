<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Администратор
 * Date: 28.09.12
 * Time: 16:29
 * To change this template use File | Settings | File Templates.
 */
class CScaffoldSubmit implements IScaffoldable {
    private $_name = null;
    private $_model = null;
    public function __construct(CModel $model,
                                $name) {

        $this->_model = $model;
        $this->_name = $name;
    }
    public function display(array $attr = null) {
        CHtml::submit($this->_name);
    }
}
