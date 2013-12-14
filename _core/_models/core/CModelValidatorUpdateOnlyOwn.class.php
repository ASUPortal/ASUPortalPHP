<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 14.12.13
 * Time: 17:08
 * To change this template use File | Settings | File Templates.
 */

class CModelValidatorUpdateOnlyOwn implements IModelValidator{
    public function getError() {
        /**
         * Слегка разный текст ошибки в зависимости от уровня доступа к задаче
         */
        if (CSession::getCurrentUser()->getLevelForCurrentTask() == ACCESS_LEVEL_NO_ACCESS) {
            return "У Вас нет доступа к текущей задаче. Изменения не сохранены";
        } elseif (CSession::getCurrentUser()->getLevelForCurrentTask() == ACCESS_LEVEL_READ_ALL ||
            CSession::getCurrentUser()->getLevelForCurrentTask() == ACCESS_LEVEL_READ_OWN_ONLY) {

            return "У Вас недостаточно прав для редактирования документа. Изменения не сохранены";
        } elseif (CSession::getCurrentUser()->getLevelForCurrentTask() == ACCESS_LEVEL_WRITE_OWN_ONLY) {
            return "Вы можете редактировать только свои записи. Изменения не сохранены";
        }
    }

    public function onCreate(CModel $model){
        /**
         * За создание этот валидатор не отвечает, поэтому всегда согласен
         */
        return true;
    }

    public function onRead(CModel $model) {
        /**
         * За создание этот валидатор не отвечает, поэтому всегда согласен
         */
        return true;
    }

    /**
     * Проверяем, что текущий пользователь имеет право редактировать записи
     *
     * @param CModel $model
     * @return bool
     */
    public function onUpdate(CModel $model) {
        $result = true;
        if (CSession::getCurrentUser()->getLevelForCurrentTask() == ACCESS_LEVEL_NO_ACCESS) {
            /**
             * Проверим, что пользователь имеет доступ к текущей задаче
             */
            $result = false;
        } elseif (CSession::getCurrentUser()->getLevelForCurrentTask() == ACCESS_LEVEL_READ_OWN_ONLY ||
            CSession::getCurrentUser()->getLevelForCurrentTask() == ACCESS_LEVEL_READ_ALL) {
            /**
             * Пользователь с правом только на чтение не может что-либо сохранять
             */
            $result = false;
        } elseif (CSession::getCurrentUser()->getLevelForCurrentTask() == ACCESS_LEVEL_WRITE_OWN_ONLY) {
            /**
             * Проверим, что текущий пользователь есть в authors-полях модели,
             * которую будет сохранять
             */
            $result = false;
            $coreModel = CCoreObjectsManager::getCoreModel($model);
            if (is_null($coreModel)) {
                /**
                 * Если модели нет, то будем считать, что отвалидировано
                 * успешно. Иначе сломается все остальное
                 */
                $result = true;
            } else {
                if ($coreModel->getAuthorsFields()->getCount() == 0) {
                    /**
                     * Не стопорим систему если она не настроена.
                     * Если поля не указаны, то не валидируем
                     */
                    $result = true;
                } else {
                    /**
                     * Проверяем, что текущий пользователь прописан в authors-полях модели
                     */
                    foreach ($coreModel->getAuthorsFields()->getItems() as $field) {
                        $fieldName = $field->field_name;
                        if ($model->$fieldName == CSession::getCurrentPerson()->getId()) {
                            $result = true;
                        }
                    }
                }
            }
        }
        return $result;
    }

    public function onDelete(CModel $model) {
        /**
         * За создание этот валидатор не отвечает, поэтому всегда согласен
         */
        return true;
    }

}