<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 14.12.13
 * Time: 20:11
 * To change this template use File | Settings | File Templates.
 */

class CModelValidatorCreationOnlyWithWriteAccess implements IModelValidator{
    public function getError() {
        /**
         * Слегка разный текст ошибки в зависимости от уровня доступа к задаче
         */
        if (CSession::getCurrentUser()->getLevelForCurrentTask() == ACCESS_LEVEL_NO_ACCESS) {
            return "У Вас нет доступа к текущей задаче. Создание новых записей запрещено";
        } elseif (CSession::getCurrentUser()->getLevelForCurrentTask() != ACCESS_LEVEL_WRITE_ALL) {

            return "У Вас недостаточно прав создания новых записей.";
        }
    }

    public function onCreate(CModel $model) {
        $result = false;
        if (CSession::getCurrentUser()->getLevelForCurrentTask() == ACCESS_LEVEL_WRITE_ALL) {
            /**
             * Пользователь может создавать что-нибудь только если у него уровень
             * Чтение и запись всех записей.
             */
            $result = true;
        }
        return $result;
    }

    public function onRead(CModel $model) {
        /**
         * За создание этот валидатор не отвечает, поэтому всегда согласен
         */
        return true;
    }

    public function onUpdate(CModel $model) {
        /**
         * За создание этот валидатор не отвечает, поэтому всегда согласен
         */
        return true;
    }

    public function onDelete(CModel $model) {
        /**
         * За создание этот валидатор не отвечает, поэтому всегда согласен
         */
        return true;
    }

}