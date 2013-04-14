<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 22.12.12
 * Time: 13:23
 * To change this template use File | Settings | File Templates.
 */
class CMessage extends CActiveModel {
    protected $_table = TABLE_MESSAGES;
    protected $_sender = null;
    protected $_recipient = null;

    public function relations() {
        return array(
            "sender" => array(
                "relationPower" => RELATION_COMPUTED,
                "storageProperty" => "_sender",
                "relationFunction" => "getSender"
            )
        );
    }

    public function fieldsProperty() {
        return array(
            'file_name' => array(
                'type'  => FIELD_UPLOADABLE,
                'upload_dir' => CORE_CWD.CORE_DS."f_mails".CORE_DS
            )
        );
    }

    public function attributeLabels() {
        return array(
            "mail_title" => "Заголовок сообщения",
            "to_user_id" => "Кому",
            "file_name" => "Вложение"
        );
    }

    /**
     * @return mixed|null
     */
    public function getTheme() {
        return $this->mail_title;
    }

    /**
     * Отправитель
     *
     * @return CPerson|null
     */
    public function getSender() {
        if (is_null($this->_sender)) {
            $user = CStaffManager::getUser($this->from_user_id);
            if (!is_null($user)) {
                $this->_sender = $user->getPerson();
            }
        }
        return $this->_sender;
    }

    /**
     * Получатель
     *
     * @return CPerson|null
     */
    public function getRecipient() {
        if (is_null($this->_recipient)) {
            $user = CStaffManager::getUser($this->to_user_id);
            if (!is_null($user)) {
                $this->_recipient = $user->getPerson();
            }
        }
        return $this->_recipient;
    }

    /**
     * Тело сообщения
     *
     * @return mixed|null
     */
    public function getBody() {
        return $this->mail_text;
    }
    public function getSendDate() {
        return date("d.m.Y H:i:s");
    }
    public function isRead() {
        return $this->read_status == 1;
    }
}
