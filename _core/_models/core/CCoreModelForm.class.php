<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 08.08.13
 * Time: 17:37
 * To change this template use File | Settings | File Templates.
 */

class CCoreModelForm extends CFormModel{
    public $fields = array();
    public $id;

    public function save() {
        $model = CCoreObjectsManager::getCoreModel($this->id);
        foreach ($this->getItems()->getItems() as $item) {
            $field = new CCoreModelField();
            $field->model_id = $model->getId();
            $field->field_name = $item["name"];
            $field->save();

            if ($item["translation"] !== "") {
                $t = new CCoreModelFieldTranslation();
                $t->field_id = $field->getId();
                $t->value = $item["translation"];
                $t->save();
            }

            if ($item["validator"] !== "0") {
                $v = new CCoreModelFieldValidator();
                $v->field_id = $field->getId();
                $v->validator_id = $item["validator"];
                $v->save();
            }
        }
    }
}