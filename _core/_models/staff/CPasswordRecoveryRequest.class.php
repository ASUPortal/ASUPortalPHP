<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Администратор
 * Date: 23.09.12
 * Time: 21:10
 * To change this template use File | Settings | File Templates.
 */
class CPasswordRecoveryRequest extends CActiveModel {
    public function attributeLabels() {
        return array(
            "credential" => "Email или ФИО"
        );
    }
    public function validationRules() {
        return array(
            "required" => array(
                "credential"
            )
        );
    }
    public function validate() {
        if (!parent::validate()) {
            return parent::validate();
        }
        $user = CStaffManager::getUser($this->credential);
        if (is_null($user)) {
            $this->getValidationErrors()->add("credential", "Пользователя с указанными данными не существует");
            return false;
        }
        return true;
    }
    /**
     * Не был ли запрос использован ранее
     *
     * @return bool
     */
    public function isActive() {
        return ($this->active == 1);
    }
}
