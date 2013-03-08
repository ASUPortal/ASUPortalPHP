<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Администратор
 * Date: 28.09.12
 * Time: 15:58
 * To change this template use File | Settings | File Templates.
 */
class CScaffoldForm implements IScaffoldable {
    private $_fields = null;
    private $_target = null;
    private $_model = null;
    private $_params = null;
    private $_method = null;
    /**
     * Форма. Просто заготовка формы
     *
     * @param CModel $model
     * @param $target
     * @param $method
     * @param array $fields
     * @param array|null $params
     */
    public function __construct(CModel $model,
                                $target,
                                array $fields,
                                $method = "POST",
                                array $params = null) {
        $this->_fields = $fields;
        $this->_model = $model;
        $this->_target = $target;
        $this->_method = $method;
        $this->_params = $params;
    }
    /**
     * @return array
     */
    private function getParams() {
        if (is_null($this->_params)) {
            $this->_params = array(
                "beforeField" => "<p>",
                "afterField" => "</p>",
                "beforeForm" => "",
                "afterForm" => ""
            );
        }
        return $this->_params;
    }
    /**
     * @param array|null $attr
     */
    public function display(array $attr = null) {
        $params = $this->getParams();
        echo $params['beforeForm'];
        echo '<form action="'.$this->_target.'" method="'.$this->_method.'">';
        foreach ($this->_fields as $field) {
            if (is_array($field)) {
                foreach ($field as $f) {
                    echo $params['beforeField'];
                    $f->display();
                    echo $params['afterField'];
                }
            } else {
                echo $params['beforeField'];
                $field->display();
                echo $params['afterField'];
            }
        }
        echo '</form>';
        echo $params['afterForm'];
    }
}
