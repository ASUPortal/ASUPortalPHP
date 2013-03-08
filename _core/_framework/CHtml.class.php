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
        echo '<select name="'.$name.'" '.$inline.'>';
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
                    $checked = "selected";
                }
            } elseif ($selected != "") {
                if ($key == $selected) {
                    $checked = "selected";
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
        $field = $model::getClassName();
        if ($multiple_key !== "") {
            $field .= "[".$multiple_key."]";
        }
        $field .= "[".$name."]";
        self::dropDownList($field, $values, $model->$name, $id, $class, $html);
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
        $field = $model::getClassName();
        if ($multiple_key !== "") {
            $field .= "[".$multiple_key."]";
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
        echo '<input type="text" name="'.$name.'" value="'.htmlspecialchars($value).'" '.$inline.'>';
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
        $field = $model::getClassName();
        if ($multiple_key !== "") {
            $field .= "[".$multiple_key."]";
        }
        $field .= "[".$name."]";
        self::textField($field, $model->$name, $id, $class, $html);
    }
    public static function activeDateField($name, CModel $model, $format = "%d.%m.%Y", $id = "", $class = "", $html = "") {
        $field = $model::getClassName()."[".$name."]";
        if ($id == "") {
            $id = $field;
        }
        if ($format == "") {
            $format = "%d.%m.%Y";
        }
        $id = str_replace("[", "_", $id);
        $id = str_replace("]", "_", $id);
        self::textField($field, $model->$name, $id, $class, $html);
        if (!self::$_calendarInit) {
            self::$_calendarInit = true;
            echo '
            <script type="text/javascript" src="'.WEB_ROOT.'scripts/calendar.js"></script>
            <script type="text/javascript" src="'.WEB_ROOT.'scripts/calendar-setup.js"></script>
            <script type="text/javascript" src="'.WEB_ROOT.'scripts/lang/calendar-ru_win_.js"></script>
            <link rel="stylesheet" type="text/css" media="all" href="'.WEB_ROOT.'css/calendar-win2k-asu.css" title="win2k-cold-1" />';
        }
        echo '
        <button type="reset" id="'.$id.'_select">...</button>
            <script type="text/javascript">
                Calendar.setup({
                    inputField     :    "'.$id.'",      // id of the input field
                    ifFormat       :    "'.$format.'",       // format of the input field "%m/%d/%Y %I:%M %p"
                    showsTime      :    false,            // will display a time selector
                    button         :    "'.$id.'_select",   // trigger for the calendar (button ID)
                    singleClick    :    true,           // double-click mode false
                    step           :    1                // show all years in drop-down boxes (instead of every other year as default)
                });
            </script>';
    }
    public static function activeTextBox($name, CModel $model, $id = "", $class = "", $html = "", $multiple_key = "") {
        $field = $model::getClassName();
        if ($multiple_key !== "") {
            $field .= "[".$multiple_key."]";
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
        echo '<input type="submit" value="'.$value.'">';
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
        echo '<textarea name="'.$name.'" '.$inline.'>'.$value.'</textarea>';
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
        echo '<input type="checkbox" name="'.$name.'" '.$checked.' '.$inline.'>';
    }
    public static function activeCheckBoxGroup($name, CModel $model, $values = null) {
        foreach ($values as $key=>$value) {
            $inputName = $model::getClassName()."[".$name."][]";
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
                die("Какой-то неподдерживаемый тип данных для построение списка ".get_class($model->$name));
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
}
