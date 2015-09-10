<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 31.03.13
 * Time: 11:34
 * To change this template use File | Settings | File Templates.
 */

class CNewsItem extends CActiveModel{
    protected $_table = TABLE_NEWS;
    protected $_author;
    public function relations() {
        return array(
            "author" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_author",
                "storageField" => "user_id_insert",
                "managerClass" => "CStaffManager",
                "managerGetObject" => "getUser"
            )
        );
    }
    public function attributeLabels() {
        return array(
            "title" => "Заголовок новости",
            "file" => "Текст новости",
            "image" => "Прикрепленное фото",
            "file_attach" => "Вложение",
            "date_time" => "Дата создания"
        );
    }
    public function validationRules() {
        return array(
            "required" => array(
                "title",
                "file",
                "date_time"
            )
        );
    }
    public function fieldsProperty() {
        return array(
            'image' => array(
                'type'  => FIELD_UPLOADABLE,
                'upload_dir' => CORE_CWD.CORE_DS."images".CORE_DS."news".CORE_DS
            ),
            'file_attach' => array(
                'type'  => FIELD_UPLOADABLE,
                'upload_dir' => CORE_CWD.CORE_DS."news".CORE_DS."attachement".CORE_DS
            )
        );
    }
    public function getImagePath() {
        /**
         * Если есть картинка, то будем показывать ее, но сразу
         * смасштабируем с помощью модуля масштабирования.
         * Если изображение не находится, то показываем стандартное
         * во всех местах
         */
        $imgPath = "";
        if ($this->image !== "") {
            if ($this->news_type == "notice") {
                $imgPath = "images/news/".$this->image;
            } else {
                $imgPath = "images/lects/".$this->image;
            }
        } else {
            /**
             * Если изображение не указано, то показываем фотку автора
             * Если автор не указан, то показываем дефолтное изображение
             */
            if (is_null($this->author)) {
                $imgPath = "";
            } else {
                /**
                 * Если у преподавателя указана фотка, то берем ее, иначе показываем
                 * стандартную
                 */
                if (is_null($this->author->getPerson())) {
                    $imgPath = "";
                } else {
                    $person = $this->author->getPerson();
                    if ($person->photo == "") {
                        $imgPath = "";
                    } else {
                        $imgPath = "images/lects/".$person->photo;
                    }
                }
            }
        }
        /**
         * Если файл с картинкой существует, то показываем его
         * Если не существует, то показываем стандартный блок Объявление
         */
        if ($imgPath == "") {
            $imgPath = "images/design/notice.gif";
        }
        if (!file_exists(CORE_CWD."/".$imgPath)) {
            $imgPath = "images/design/notice.gif";
        }
        return $imgPath;
    }

    /**
     * Дата публикации
     *
     * @return string
     */
    public function getPublicationDate() {
        return date("d.m.Y", strtotime($this->date_time));
    }

    /**
     * ФИО автора
     *
     * @return string
     */
    public function getAuthorName() {
        /**
         * Если включена защита персональных данных, то не показываем
         * ФИО. Также не показываем ее если пользователь не авторизован
         */
        if (CSettingsManager::getSettingValue("hide_personal_data")) {
            if (CSession::isAuth()) {
                if (is_null($this->author)) {
                    return "";
                } else {
                    if (is_null($this->author->getPerson())) {
                        return "";
                    } else {
                        return $this->author->getPerson()->getName();
                    }
                }
            }
        } else {
            if (is_null($this->author)) {
                return "";
            } else {
                if (is_null($this->author->getPerson())) {
                    return "";
                } else {
                    return $this->author->getPerson()->getName();
                }
            }
        }
    }

    /**
     * Ссылка на страницу преподавателя, если это разрешено
     *
     * @return string
     */
    public function getAuthorLink() {
        if (!CSettingsManager::getSettingValue("hide_personal_data")) {
            if (is_null($this->author)) {
                return "";
            } else {
                return WEB_ROOT."_modules/_lecturers/index.php?action=view&id=".$this->author->getId();
            }
        } else {
            return "";
        }
    }

    /**
     * Ссылка на вложение
     *
     * @return string
     */
    public function getAttachLink() {
        if ($this->file_attach !== "") {
            if (file_exists(CORE_CWD."/news/attachement/".$this->file_attach)) {
                return WEB_ROOT."news/attachement/".$this->file_attach;
            } else {
                return "";
            }
        }
        return "";
    }

    /**
     * Текст новости
     *
     * @return mixed|null
     */
    public function getBody() {
        $s = $this->file;
        /**
         * Сохраняем совместимость со старой версией
         */
        $s=str_replace ("\r\n","<br>",$s);
        $s=str_replace ("[url]","<a href='http://",$s);
        $s=str_replace ("[/url]","' target='_blank' style='font-weight:normal; text-decoration:underline;'>Ресурс</a>",$s);
        $s=str_replace ("[quote]","<u>Цитата</u><br><span style='color:grey;background:white;'>",$s);
        $s=str_replace ("[/quote]","</span><br>",$s);
        $s=str_replace ("[b]","<b>",$s);
        $s=str_replace ("[/b]","</b>",$s);
        $s=str_replace ("[u]","<u>",$s);
        $s=str_replace ("[/u]","</u>",$s);
        $s=str_replace ("[i]","<i>",$s);
        $s=str_replace ("[/i]","</i>",$s);
        /**
         * Если новость получилась длинная, то сокращаем ее
         */
        $result = "";
        if (mb_strlen($s) > 200) {
            $result = '<div id="full_'.$this->getId().'" style="display: none;">';
            $result .= $s;
            $result .= '</div>';
            $result .= '<div id="preview_'.$this->getId().'">';
            $result .= mb_substr($s, 0, 200);
            $result .= '<div style="clear: both; "></div>';
            $result .= '<div class="asu_more" onclick="news_show_full('.$this->getId().');">Подробнее</div>';
            $result .= '</div>';
        } else {
            $result = '<div id="full_'.$this->getId().'" style="display: block;">';
            $result .= $s;
            $result .= '</div>';
        }
        return $result;
    }
}