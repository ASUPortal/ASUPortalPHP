<?php
/**
 * Created by JetBrains PhpStorm.
 * User: TERRAN
 * Date: 06.05.12
 * Time: 10:38
 * To change this template use File | Settings | File Templates.
 *
 * Вывод html-блоков нормального вида. Унификация рулит!
 */
class CHtml {
    private static $_calendarInit = false;
    private static $_multiselectInit = false;
    public static function button($value, $onClick = "") {
        echo '<input type="button" value="'.$value.'" onclick="'.$onClick.'">';
    }
    /**
     * Вывод элемента div
     *
     * @static
     * @param $id
     * @param string $content
     * @param string $class
     * @param string $html
     */
    public static function div($id, $content = "", $class = "", $html = "") {
        $inline = "";
        if ($id != "") {
            $inline .= ' id="'.$id.'"';
        }
        if ($class != "") {
            $inline .= ' class="'.$class.'"';
        }
        if ($html != "") {
            $inline .= $html;
        }
        echo '<div'.$inline.">".$content."</div>";
    }
    /**
     * Вывод выпадающего списка
     * $values - простой array вида [ключ]=>значение для подстановки
     * $selected - выбранный элемент
     *
     * @static
     * @param $name
     * @param $values
     * @param null $selected
     * @param string $id
     * @param string $class
     * @param string $html
     */
    public static function dropDownList($name, $values, $selected = null, $id = "", $class = "", $html = "") {
        $inline = "";
        if ($id != "") {
            $inline .= ' id="'.$id.'"';
        }
        if ($class != "") {
            $inline .= ' class="'.$class.'"';
        }
        if ($html != "") {
            $inline .= $html;
        }
        /**
         * Проверим, какого вида ключи у подставляемых значений.
         * Если числовые, то проверяем наличие нуля, если ключи нечисловые, то
         * не добавляем лишнего выбиратора
         */
        $numeric = true;
        foreach ($values as $key=>$value) {
            if (!is_numeric($key)) {
                $numeric = false;
            }
        }
        if ($numeric) {
            if (!array_key_exists(0, $values)) {
                $values[0] = "- Выберите из списка (".count($values).") -";
            }
        }
        echo '<select data-dojo-type="dijit/form/Select" style="width: 30em;  " name="'.$name.'" '.$inline.'>';
        // часто выбор делается из словаря, так что преобразуем объекты CTerm к строке
        foreach ($values as $key=>$value) {
            if (is_object($value)) {
                if (strtoupper(get_class($value)) == "CTERM") {
                    $values[$key] = $value->getValue();
                }
            }
        }
        foreach ($values as $key=>$value) {
            $checked = "";
            if (is_null($selected)) {
                if ($key == 0) {
                    $checked = 'selected="selected"';
                }
            } elseif ($selected != "") {
                if ($key == $selected) {
                    $checked = 'selected="selected"';
                }
            }
            echo '<option '.$checked.' value="'.$key.'">'.$value.'</option>';
        }
        echo '</select>';
    }
    /**
     * @static
     * @param $name
     * @param CModel $model
     * @param $values
     * @param string $id
     * @param string $class
     * @param string $html
     */
    public static function activeDropDownList($name, CModel $model, $values, $id = "", $class = "", $html = "", $multiple_key = "") {
        /**
         * Безумно полезная штука для работы со связанными
         * моделями. Если в названии поля есть скобки, то производится
         * разбор вида подмодель[ее поле]
         */
        $submodelName = "";
        if (strpos($name, "[") !== false) {
            $submodelName = substr($name, 0, strpos($name, "["));
            $name = substr($name, strpos($name, "[") + 1);
            $name = substr($name, 0, strlen($name) - 1);
            $model = $model->$submodelName;
        }
        $field = $model::getClassName();
        if ($multiple_key !== "") {
            $field .= "[".$multiple_key."]";
        }
        if ($submodelName !== "") {
            $field .= "[".$submodelName."]";
        }
        $field .= "[".$name."]";
        $fieldRequired = false;
        if (array_key_exists("selected", $model->getValidationRules())) {
            $rules = $model->getValidationRules();
            $required = $rules["selected"];
            if (in_array($name, $required)) {
                $html .= " required";
                $fieldRequired = true;
            }
        }
        self::dropDownList($field, $values, $model->$name, $id, $class, $html);
        if ($fieldRequired) {
            self::requiredStar();
        }
    }
    /**
     * Вывод ссылки
     *
     * @static
     * @param $text
     * @param $anchor
     * @param string $id
     * @param string $class
     * @param string $html
     */
    public static function link($text, $anchor, $id = "", $class = "", $html = "") {
        $inline = "";
        if ($id != "") {
            $inline .= ' id="'.$id.'"';
        }
        if ($class != "") {
            $inline .= ' class="'.$class.'"';
        }
        if ($html != "") {
            $inline .= $html;
        }
        echo '<a href="'.$anchor.'" '.$inline.'>'.$text.'</a>';
    }
    /**
     * Скрытое поле
     *
     * @static
     * @param $name
     * @param $value
     */
    public static function hiddenField($name, $value, $id = "") {
        echo '<input type="hidden" name="'.$name.'" ';
        if ($id != "") {
            echo 'id="'.$id.'" ';
        }
        echo 'value="'.$value.'">';
    }
    /**
     * Скрытое поле с автозаполнением значения из модели
     *
     * @static
     * @param $name
     * @param CActiveModel $model
     */
    public static function activeHiddenField($name, CModel $model, $multiple_key = "") {
        /**
         * Безумно полезная штука для работы со связанными
         * моделями. Если в названии поля есть скобки, то производится
         * разбор вида подмодель[ее поле]
         */
        $submodelName = "";
        if (strpos($name, "[") !== false) {
            $submodelName = substr($name, 0, strpos($name, "["));
            $name = substr($name, strpos($name, "[") + 1);
            $name = substr($name, 0, strlen($name) - 1);
            $model = $model->$submodelName;
        }
        $field = $model::getClassName();
        if ($multiple_key !== "") {
            $field .= "[".$multiple_key."]";
        }
        if ($submodelName !== "") {
            $field .= "[".$submodelName."]";
        }
        $field .= "[".$name."]";
        self::hiddenField($field, $model->$name);
    }
    /**
     * Однострочное текстовое поле
     *
     * @static
     * @param $name
     * @param $value
     * @param string $id
     * @param string $class
     * @param string $html
     */
    public static function textField($name, $value = null, $id = "", $class = "", $html = "") {
        if ($id == "") {
            $id = $name;
        }
        $inline = "";
        if ($id != "") {
            $inline .= ' id="'.$id.'"';
        }
        if ($class != "") {
            $inline .= ' class="'.$class.'"';
        }
        if ($html != "") {
            $inline .= $html;
        }
        echo '<input data-dojo-type="dijit/form/TextBox" style="width: 30em; " type="text" name="'.$name.'" value="'.htmlspecialchars($value).'" '.$inline.'>';
    }
    /**
     * Активное текстовое поле
     *
     * @static
     * @param $name
     * @param CActiveModel $model
     * @param string $id
     * @param string $class
     * @param string $html
     */
    public static function activeTextField($name, CModel $model, $id = "", $class = "", $html = "", $multiple_key = "") {
        /**
         * Безумно полезная штука для работы со связанными
         * моделями. Если в названии поля есть скобки, то производится
         * разбор вида подмодель[ее поле]
         */
        $submodelName = "";
        if (strpos($name, "[") !== false) {
            $submodelName = substr($name, 0, strpos($name, "["));
            $name = substr($name, strpos($name, "[") + 1);
            $name = substr($name, 0, strlen($name) - 1);
            $model = $model->$submodelName;
        }
        $field = $model::getClassName();
        if ($multiple_key !== "") {
            $field .= "[".$multiple_key."]";
        }
        if ($submodelName !== "") {
            $field .= "[".$submodelName."]";
        }
        $field .= "[".$name."]";
        $fieldRequired = false;
        if (array_key_exists("required", $model->getValidationRules())) {
            $rules = $model->getValidationRules();
            $required = $rules["required"];
            if (in_array($name, $required)) {
                $html .= " required";
                $fieldRequired = true;
            }
        }
        self::textField($field, $model->$name, $id, $class, $html);
        if ($fieldRequired) {
            self::requiredStar();
        }
    }
    public static function activeTimeField($name, CModel $model, $format = "%d.%m.%Y", $id = "", $class = "", $html = "", $multiple_key = "") {
        /**
         * Безумно полезная штука для работы со связанными
         * моделями. Если в названии поля есть скобки, то производится
         * разбор вида подмодель[ее поле]
         */
        $submodelName = "";
        if (strpos($name, "[") !== false) {
            $submodelName = substr($name, 0, strpos($name, "["));
            $name = substr($name, strpos($name, "[") + 1);
            $name = substr($name, 0, strlen($name) - 1);
            $model = $model->$submodelName;
        }
        $field = $model::getClassName();
        if ($multiple_key !== "") {
            $field .= "[".$multiple_key."]";
        }
        if ($submodelName !== "") {
            $field .= "[".$submodelName."]";
        }
        $field .= "[".$name."]";
        if ($id == "") {
            $id = $name;
        }
        $inline = "";
        if ($id != "") {
            $inline .= ' id="'.$id.'"';
        }
        if ($class != "") {
            $inline .= ' class="'.$class.'"';
        }
        if ($html != "") {
            $inline .= $html;
        }
        echo '<input data-dojo-props="hasDownArrow: false, autoWidth: false" data-dojo-type="dijit/form/TimeTextBox" style="width: 30em; " type="text" name="'.$field.'" value="'.htmlspecialchars($model->$name).'" '.$inline.'>';
    }
    public static function activeDateField($name, CModel $model, $format = "%d.%m.%Y", $id = "", $class = "", $html = "", $multiple_key = "") {
        /**
         * Безумно полезная штука для работы со связанными
         * моделями. Если в названии поля есть скобки, то производится
         * разбор вида подмодель[ее поле]
         */
        $submodelName = "";
        if (strpos($name, "[") !== false) {
            $submodelName = substr($name, 0, strpos($name, "["));
            $name = substr($name, strpos($name, "[") + 1);
            $name = substr($name, 0, strlen($name) - 1);
            $model = $model->$submodelName;
        }
        $field = $model::getClassName();
        if ($multiple_key !== "") {
            $field .= "[".$multiple_key."]";
        }
        if ($submodelName !== "") {
            $field .= "[".$submodelName."]";
        }
        $field .= "[".$name."]";
        if ($id == "") {
            $id = $name;
        }
        $inline = "";
        if ($id != "") {
            $inline .= ' id="'.$id.'"';
        }
        if ($class != "") {
            $inline .= ' class="'.$class.'"';
        }
        if ($html != "") {
            $inline .= $html;
        }
        echo '<input data-dojo-props="hasDownArrow: false, autoWidth: false" data-dojo-type="dijit/form/DateTextBox" style="width: 30em; " type="text" name="'.$field.'" value="'.htmlspecialchars($model->$name).'" '.$inline.'>';
    }
    public static function activeEditor($name, CModel $model, $id = "", $class = "", $html = "") {
        /**
         * Безумно полезная штука для работы со связанными
         * моделями. Если в названии поля есть скобки, то производится
         * разбор вида подмодель[ее поле]
         */
        $submodelName = "";
        if (strpos($name, "[") !== false) {
            $submodelName = substr($name, 0, strpos($name, "["));
            $name = substr($name, strpos($name, "[") + 1);
            $name = substr($name, 0, strlen($name) - 1);
            $model = $model->$submodelName;
        }
        $field = $model::getClassName();
        if ($submodelName !== "") {
            $field .= "[".$submodelName."]";
        }
        $field .= "[".$name."]";
        if ($id == "") {
            $id = $name;
        }
        $inline = "";
        if ($id != "") {
            $inline .= ' id="'.$id.'"';
        }
        if ($class != "") {
            $inline .= ' class="'.$class.'"';
        }
        if ($html != "") {
            $inline .= $html;
        }
        echo '<textarea data-dojo-type="dijit/Editor" style="width: 362px; height: 150px; " name="'.$name.'" '.$inline.'>'.$model->$name.'</textarea>';
    }
    public static function activeTextBox($name, CModel $model, $id = "", $class = "", $html = "", $multiple_key = "") {
        /**
         * Безумно полезная штука для работы со связанными
         * моделями. Если в названии поля есть скобки, то производится
         * разбор вида подмодель[ее поле]
         */
        $submodelName = "";
        if (strpos($name, "[") !== false) {
            $submodelName = substr($name, 0, strpos($name, "["));
            $name = substr($name, strpos($name, "[") + 1);
            $name = substr($name, 0, strlen($name) - 1);
            $model = $model->$submodelName;
        }
        $field = $model::getClassName();
        if ($multiple_key !== "") {
            $field .= "[".$multiple_key."]";
        }
        if ($submodelName !== "") {
            $field .= "[".$submodelName."]";
        }
        $field .= "[".$name."]";
        self::textBox($field, $model->$name, $id, $class, $html);
    }
    /**
     * Поле для ввода пароля
     *
     * @static
     * @param $name
     * @param null $value
     * @param string $id
     * @param string $class
     * @param string $html
     */
    public static function passwordField($name, $value = null, $id = "", $class = "", $html = "") {
        if ($id == "") {
            $id = $name;
        }
        $inline = "";
        if ($id != "") {
            $inline .= ' id="'.$id.'"';
        }
        if ($class != "") {
            $inline .= ' class="'.$class.'"';
        }
        if ($html != "") {
            $inline .= $html;
        }
        echo '<input type="password" name="'.$name.'" value="'.$value.'" '.$inline.'>';
    }
    /**
     * Метка
     *
     * @static
     * @param $text
     * @param $for
     */
    public static function label($text, $for) {
        echo '<label for="'.$for.'">'.$text.'</label>';
    }
    /**
     * Метка, привязанная к модели
     *
     * @static
     * @param $name
     * @param CActiveModel $model
     */
    public static function activeLabel($name, CModel $model) {
        /**
         * Безумно полезная штука для работы со связанными
         * моделями. Если в названии поля есть скобки, то производится
         * разбор вида подмодель[ее поле]
         */
        if (strpos($name, "[") !== false) {
            $modelName = substr($name, 0, strpos($name, "["));
            $name = substr($name, strpos($name, "[") + 1);
            $name = substr($name, 0, strlen($name) - 1);
            $model = $model->$modelName;
        }
        if (array_key_exists($name, $model->attributeLabels())) {
            $labels = $model->attributeLabels();
            $field = $model::getClassName()."[".$name."]";
            self::label($labels[$name], $field);
        } else {
            $field = $model::getClassName()."[".$name."]";
            self::label($name, $field);
        }
    }
    /**
     * Кнопка отправки формы
     *
     * @static
     * @param $value
     */
    public static function submit($value) {
        echo '<input type="submit" data-dojo-type="dijit/form/Button" label="'.$value.'" type="submit" value="'.$value.'">';
    }
    /**
     * Большое поле для ввода
     *
     * @static
     * @param $name
     * @param null $value
     * @param string $id
     * @param string $class
     * @param string $html
     */
    public static function textBox($name, $value = null, $id = "", $class = "", $html = "") {
        if ($id == "") {
            $id = $name;
        }
        $inline = "";
        if ($id != "") {
            $inline .= ' id="'.$id.'"';
        }
        if ($class != "") {
            $inline .= ' class="'.$class.'"';
        }
        if ($html != "") {
            $inline .= $html;
        }
        echo '<textarea data-dojo-type="dijit/form/Textarea" style="width: 362px; height: 150px; " name="'.$name.'" '.$inline.'>'.$value.'</textarea>';
    }
    public static function checkBox($name, $value, $checked = false, $id = "", $class = "", $html = "") {
        if ($id == "") {
            $id = $name;
        }
        $inline = "";
        if ($id != "") {
            $inline .= ' id="'.$id.'"';
        }
        if ($class != "") {
            $inline .= ' class="'.$class.'"';
        }
        if ($html != "") {
            $inline .= $html;
        }
        if ($value != "") {
            $inline .= ' value="'.$value.'"';
        }
        if ($checked) {
            $checked = "checked";
        } else {
            $checked = "";
        }
        echo '<input data-dojo-type="dijit/form/CheckBox" type="checkbox" name="'.$name.'" '.$checked.' '.$inline.'>';
    }
    public static function activeCheckBoxGroup($name, CModel $model, $values = null) {
        /**
         * Безумно полезная штука для работы со связанными
         * моделями. Если в названии поля есть скобки, то производится
         * разбор вида подмодель[ее поле]
         */
        $submodelName = "";
        if (strpos($name, "[") !== false) {
            $submodelName = substr($name, 0, strpos($name, "["));
            $name = substr($name, strpos($name, "[") + 1);
            $name = substr($name, 0, strlen($name) - 1);
            $model = $model->$submodelName;
        }
        foreach ($values as $key=>$value) {
            $inputName = $model::getClassName();
            if ($submodelName !== "") {
                $inputName .= "[".$submodelName."]";
            }
            $inputName .= "[".$name."][]";
            echo '<input type="checkbox" name="'.$inputName.'"';
            if (is_array($model->$name)) {
                if (array_key_exists($key, $model->$name)) {
                    echo ' checked';
                } elseif (in_array($key, $model->$name)) {
                    echo ' checked';
                }
            } elseif (is_object($model->$name)) {
                if (strtolower(get_class($model->$name)) == "carraylist") {
                    $list = $model->$name;
                    if ($list->hasElement($key)) {
                        echo ' checked';
                    }
                }
            } else {
                echo ("Какой-то неподдерживаемый тип данных для построение списка ".get_class($model->$name));
                exit;
            }
            echo ' value="'.$key.'">'.$value.'<br>';
        }
    }
    public static function activeRadioButtonGroup($name, CModel $model, $values = array(), $groupName = "") {
        foreach ($values as $key=>$value) {
            $inputName = $model::getClassName()."[".$name.$groupName."][]";
            echo '<input type="radio" name="'.$inputName.'"';
            if (is_array($model->$name)) {
                if (array_key_exists($key, $model->$name)) {
                    echo ' checked';
                } elseif (in_array($key, $model->$name)) {
                    echo ' checked';
                }
            } else {
                if ($model->$name == $value) {
                    echo ' checked';
                } elseif ($model->$name == $key) {
                    echo ' checked';
                }
            }
            echo ' value="'.$key.'">'.$value.'<br>';
        }
    }
    public static function activeCheckBox($name, CModel $model, $id = "", $class = "", $html = "") {
        /**
         * Безумно полезная штука для работы со связанными
         * моделями. Если в названии поля есть скобки, то производится
         * разбор вида подмодель[ее поле]
         */
        $submodelName = "";
        if (strpos($name, "[") !== false) {
            $submodelName = substr($name, 0, strpos($name, "["));
            $name = substr($name, strpos($name, "[") + 1);
            $name = substr($name, 0, strlen($name) - 1);
            $model = $model->$submodelName;
        }
        if ($model->$name == true) {
            $name = $model::getClassName()."[".$name."]";
            self::checkBox($name, "1", true, $id, $class, $html);
        } else {
            $name = $model::getClassName()."[".$name."]";
            self::checkBox($name, "1", false, $id, $class, $html);
        }
    }
    public static function error($name, CModel $model) {
        if ($model->getValidationErrors()->hasElement($name)) {
            echo "<p>".$model->getValidationErrors()->getItem($name)."</p>";
        }
    }
    public static function activeMultiSelect($name, CModel $model, $values = array()) {
        /**
         * Безумно полезная штука для работы со связанными
         * моделями. Если в названии поля есть скобки, то производится
         * разбор вида подмодель[ее поле]
         */
        $submodelName = "";
        if (strpos($name, "[") !== false) {
            $submodelName = substr($name, 0, strpos($name, "["));
            $name = substr($name, strpos($name, "[") + 1);
            $name = substr($name, 0, strlen($name) - 1);
            $model = $model->$submodelName;
        }
        $field = $model::getClassName();
        if ($submodelName !== "") {
            $field .= "[".$submodelName."]";
        }
        $field .= "[".$name."][]";
        /**
         * Новая версия на dojo asu/asuMultiSelect
         *
         * Состоит из двух частей - выбиралки из списка и
         * списка выбранных значений
         */
        echo '<script>
            require(["asu/asuMultiSelect"]);
        </script>';
        echo '<div data-dojo-type="asu.asuMultiSelect" data-dojo-props="fieldName: \''.$field.'\'">';
        /**
         * Список со значениями для поиска
         */
        echo '<tr><td colspan="2">';
        echo '<select id="_selector" style="width: 30em; ">';
        foreach ($values as $key=>$value) {
            echo '<option value="'.$key.'">'.$value.'</option>';
        }
        echo '</select>';
        /**
         * Список выбранных значений
         */
        echo '<select id="_display" multiple size="6" style="width: 30em; margin-left: 200px; ">';
        foreach ($model->$name->getItems() as $f) {
            echo '<option value="'.$f->getId().'">'.$f->getName().'</option>';
        }
        echo '</select>';
        /**
         * Удалялка
         */
        echo '<span id="_deleter" style="cursor: pointer; "><img src="'.WEB_ROOT.'images/todelete.png"></span>';
        /**
         * Список значений, которые будут отданы на сервер
         */
        foreach ($model->$name->getItems() as $f) {
            echo '<input type="hidden" name="'.$field.'" value="'.$f->getId().'">';
        }
        echo '</div>';
    }
    public static function activeSelect($name, CModel $model, $values = array(), $isMultiple = false, $size = 5, $id = "") {
        echo '<select name="'.$model::getClassName().'['.$name.'][]" size="'.$size.'" ';
        if ($isMultiple) {
            echo 'multiple';
        }
        if ($id != "") {
            echo ' id="'.$id.'"';
        }
        echo '>';
        // часто выбор делается из словаря, так что преобразуем объекты CTerm к строке
        foreach ($values as $key=>$value) {
            if (is_object($value)) {
                if (strtoupper(get_class($value)) == "CTERM") {
                    $values[$key] = $value->getValue();
                }
            }
        }
        foreach ($values as $k=>$v) {
            if (is_array($v)) {
                echo '<optgroup label="'.$k.'">';
                foreach ($v as $key=>$value) {
                    echo '<option value="'.$key.'" ';
                    if (is_array($model->$name)) {
                        if (array_key_exists($key, $model->$name)) {
                            echo 'selected';
                        }
                    } elseif (is_object($model->$name)) {
                        if ($model->$name->hasElement($key)) {
                            echo 'selected';
                        }
                    }
                    echo '>'.$value.'</option>';
                }
                echo '</optgroup>';
            } else {
                echo '<option value="'.$k.'" ';
                if (is_array($model->$name)) {
                    if (array_key_exists($k, $model->$name)) {
                        echo 'selected';
                    }
                } elseif (is_object($model->$name)) {
                    if ($model->$name->hasElement($k)) {
                        echo 'selected';
                    }
                }
                echo '>'.$v.'</option>';
            }
        }
        echo '</select>';
    }
    /**
     * Обработчик фильтра сотрудников по типу
     *
     * @static
     * @param $fieldId
     */
    public static function personTypeFilter($field, CModel $model) {
        $fieldId = $model::getClassName()."[".$field."]";
        echo '<span id="person_type_selector_button" style="cursor: pointer; " onclick="showPersonTypeSelector(); return false;"><img src="'.WEB_ROOT.'images/filter.gif">';
        echo '</span>';
        echo '<div id="person_type_selector" style="position: absolute; display: none; border: 1px solid #c0c0c0; background: #ffffff; padding: 5px; z-index: 100; margin-left: 300px; margin-top: -5px; ">';
        foreach (CTaxonomyManager::getCacheTypes()->getItems() as $type) {
            echo '<span><input type="checkbox" onclick="updatePersonListField(\''.$fieldId.'\'); return true; " value="'.$type->getId().'" checked>'.$type->getValue().'</span><br>';
        }
        echo '</div>';
    }
    public static function paginator(CPaginator $paginator, $action) {
        echo '<div class="asu_paginator">';
        echo '<span>Страницы: </span>';
        foreach ($paginator->getPagesList($action) as $page=>$link) {
        	if (CRequest::getString("order") !== "") {
        		$link = $link."&order=".CRequest::getString("order");
        	}
        	if (CRequest::getString("direction") !== "") {
        		$link = $link."&direction=".CRequest::getString("direction");
        	}
            if (CRequest::getString("filter") !== "") {
                $link = $link."&filter=".CRequest::getString("filter");
            }
            echo '<span style="padding: 5px; "><a href="'.$link.'">'.$page.'</a></span>';
        }
        echo '<span>Текущая страница: '.$paginator->getCurrentPageNumber().' </span>';
        echo '<span>Всего: '.$paginator->getPagesCount().'</span>';
        echo '</div>';
    }
    public static function helpForCurrentPage() {
        if (!is_null(CHelpManager::getHelpForCurrentPage())) {
            echo '<div class="asu_help_block">'.CHelpManager::getHelpForCurrentPage()->content.'</div>';
        }
    }
    public static function errorSummary(CModel $model) {

    }
    public static function activeUpload($name, CModel $model) {
        $field = $model::getClassName()."[".$name."]";
        echo '<input type="file" name="'.$field.'">';
    }
    /**
     * Печать по шаблону
     * @param $template
     */
    public static function printOnTemplate($template) {
    	$formset = CPrintManager::getFormset($template);
    	if (!is_null($formset)) {
    		$forms = $formset->activeForms;
    		$variables = $formset->computeTemplateVariables();
    		echo '<ul>';
    		foreach ($forms->getItems() as $form) {
    			$url = "?action=print".
      			"&manager=".$variables['manager'].
    			"&method=".$variables['method'].
    			"&id=".$variables['id'].
    			"&template=".$form->getId();
    			echo '<li><a href="'.WEB_ROOT.'_modules/_print/'.$url.'" target="_blank">'.$form->title.'</a></li>';
    		}
    		echo '</ul>';
    	}
    }

    /**
     * Подготоваливает к вывод данные для печати группы
     * записей по указанному шаблону
     *
     * @param $template
     */
    public static function printGroupOnTemplate($template) {
        $formset = CPrintManager::getFormset($template);
        if (!is_null($formset)) {
            $forms = $formset->activeForms;
            $variables = $formset->computeTemplateVariables();
            echo "<ul>";
            foreach ($forms->getItems() as $form) {
                echo '<li><a href="#" onclick="printWithTemplate(';
                echo "'".$variables['manager']."'";
                echo ", '".$variables['method']."'";
                echo ", '".$form->getId()."'";
                echo '); return false;">'.$form->title.'</a></li>';
            }
            echo "</ul>";
        }
    }
    public static function tableOrder($field, CModel $model = null) {
        if (is_null($model)) {
            return "";
        }
    	$label = $model->getAttributeLabel($field);
    	if (CRequest::getString("action") !== "") {
    		$actions[] = "action=".CRequest::getString("action");
    	}
    	if (CRequest::getInt("page") !== 0) {
    		$actions[] = "page=".CRequest::getInt("page");
    	}
        if (CRequest::getString("filter") !== "") {
            $actions[] = "filter=".CRequest::getString("filter");
        }
    	$actions[] = "order=".$field;
    	if (CRequest::getString("order") == $field) {
    		if (CRequest::getString("direction") == "") {
    			$actions[] = "direction=asc";
    		} elseif (CRequest::getString("direction") == "asc") {
    			$actions[] = "direction=desc";
    		} elseif (CRequest::getString("direction") == "desc") {
    			$actions[] = "direction=asc";
    		}
    		$label = '<a href="?'.implode($actions, "&").'">'.$label.'</a>';
    	} else {
    		$actions[] = "direction=desc";
    		$label = '<a href="?'.implode($actions, "&").'">'.$label.'</a>';
    	}
    	echo $label;
    }
    public static function activeNamesSelect($field, CModel $model) {
        echo '
        <table border="0" cellpadding="2" cellspacing="0" class="tableBlank" style="width: 300px; ">
            <tr>
                <td><input type="text" name="'.$field.'" id="'.$field.'" style="width: 97%; "></td>
                <td style="width: 16px; "><img src="'.WEB_ROOT.'images/tango/22x22/actions/edit-find.png" id="'.$field.'_selector" style="height: 19px; "></td>
            </tr>
            <tr>
                <td valign="top">
                    <select id="'.$field.'_select" style="width: 100%; border: none; " size="5">';
        foreach ($model->$field->getItems() as $entry) {
            echo '<option value="'.$entry->getId().'" type="'.$entry->getType().'">'.$entry->getName().'</option>';
        }
        echo '      </select>
                </td>
                <td valign="top"><img src="'.WEB_ROOT.'images/tango/22x22/actions/edit-clear.png" id="'.$field.'_deleter" style="height: 19px; "></td>
            </tr>
        </table>';
        foreach ($model->$field->getItems() as $entry) {
            echo '
            <input type="hidden" name="'.$field.'[id][]" value="'.$entry->getId().'">
            <input type="hidden" name="'.$field.'[name][]" value="'.$entry->getName().'">
            <input type="hidden" name="'.$field.'[type][]" value="'.$entry->getType().'">
            ';
        }
    }

    /**
     * Звездочка для отметки обязательности поля
     */
    private static function requiredStar() {
        echo '<span class="field_required">*</span>';
    }
}
