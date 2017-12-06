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
    private static $_clocksInit = false;
    private static $_multiselectInit = false;
    private static $_printFormViewInit = false;
    private static $_catalogLookupInit = false;
    private static $_uploadWidgetInit = false;
    private static $_clearboxInit = false;
    private static $_viewGroupSelectInit = false;
    private static $_widgetsIndex = 0;
    private static $_componentsInit = false;

    protected static function getFielsizeClass() {
        $result = "span5";
        if (!is_null(CSession::getCurrentUser())) {
            if (!is_null(CSession::getCurrentUser()->getPersonalSettings())) {
                if (CSession::getCurrentUser()->getPersonalSettings()->portal_input_size != "") {
                    $result = CSession::getCurrentUser()->getPersonalSettings()->portal_input_size;
                }
            }
        }
        return $result;
    }
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
    public static function dropDownList($name, $values, $selected = null, $id = "", $class = "", $html = "", $tooltipTitle = null, $sort = false) {
        $inline = "";
        if ($id != "") {
            $inline .= ' id="'.$id.'"';
        }
        if ($class != "") {
            $inline .= ' class="'.$class.'"';
        } else {
            $inline .= ' class="'.self::getFielsizeClass().'"';
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
        echo '<select name="'.$name.'" data-toggle="tooltip" title="'.$tooltipTitle.'" '.$inline.'>';
        // часто выбор делается из словаря, так что преобразуем объекты CTerm к строке
        foreach ($values as $key=>$value) {
            if (is_object($value)) {
                if (strtoupper(get_class($value)) == "CTERM") {
                    $values[$key] = $value->getValue();
                }
            }
        }
        if (!$sort) {
            asort($values);
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
    public static function activeDropDownList($name, CModel $model, $values, $id = "", $class = "", $html = "", $multiple_key = "", $sort = false) {
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
        $validators = CCoreObjectsManager::getFieldValidators($model);
        if (array_key_exists($name, $validators)) {
            $fieldRequired = true;
        }
        $tooltipTitle = null;
        if (!is_null(CCoreObjectsManager::getCoreModelFieldByFieldName($model, $name))) {
            $tooltipTitle = CCoreObjectsManager::getCoreModelFieldByFieldName($model, $name)->comment;
        }
        $inline = "";
        $inline .= $model->restrictionAttribute();
        if ($html != "") {
            $inline .= " ".$html;
        }
        self::dropDownList($field, $values, $model->$name, $id, $class, $inline, $tooltipTitle, $sort);
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
        if (is_array($value)) {
            foreach ($value as $val) {
                echo '<input type="hidden" name="'.$name.'[]" value="'.$val.'" />';
            }
        } else {
            echo '<input type="hidden" name="'.$name.'" ';
            if ($id != "") {
                echo 'id="'.$id.'" ';
            }
            echo 'value="'.$value.'">';
        }
    }
    /**
     * Скрытое поле с автозаполнением значения из модели
     *
     * @static
     * @param $name
     * @param CActiveModel $model
     */
    public static function activeHiddenField($name, CModel $model, $multiple_key = "", $value = "", $id = "") {
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
        if ($value == "") {
            $value = $model->$name;
        }
        self::hiddenField($field, $value, $id);
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
    public static function textField($name, $value = null, $id = "", $class = "", $html = "", $tooltipTitle = null) {
        if ($id == "") {
            $id = $name;
        }
        $inline = "";
        if ($id != "") {
            $inline .= ' id="'.$id.'"';
        }
        if ($class == "") {
            $class = self::getFielsizeClass();
        }
        if ($class != "") {
            $inline .= ' class="'.$class.'"';
        }
        if ($html != "") {
            $inline .= $html;
        }
        echo '<input type="text" name="'.$name.'" data-toggle="tooltip" title="'.$tooltipTitle.'" value="'.htmlspecialchars($value).'" '.$inline.'>';
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
        $validators = CCoreObjectsManager::getFieldValidators($model);
        if (array_key_exists($name, $validators)) {
            $fieldRequired = true;
        }
        $tooltipTitle = null;
        if (!is_null(CCoreObjectsManager::getCoreModelFieldByFieldName($model, $name))) {
            $tooltipTitle = CCoreObjectsManager::getCoreModelFieldByFieldName($model, $name)->comment;
        }
        $inline = "";
        $inline .= $model->restrictionAttribute();
        if ($html != "") {
            $inline .= " ".$html;
        }
        self::textField($field, $model->$name, $id, $class, $inline, $tooltipTitle);
        if ($fieldRequired) {
            self::requiredStar();
        }
    }
    public static function activeTimeField($name, CModel $model, $id = "", $class = "", $html = "") {
        $field = $model::getClassName()."[".$name."]";
        if ($id == "") {
            $id = $field;
        }
        $id = str_replace("[", "_", $id);
        $id = str_replace("]", "_", $id);
        $tooltipTitle = null;
        if (!is_null(CCoreObjectsManager::getCoreModelFieldByFieldName($model, $name))) {
            $tooltipTitle = CCoreObjectsManager::getCoreModelFieldByFieldName($model, $name)->comment;
        }
        $inline = "";
        $inline .= $model->restrictionAttribute();
        if ($html != "") {
            $inline .= " ".$html;
        }
        ?>
        <div class="input-append bootstrap-timepicker">
            <input id="<?php echo $id; ?>" type="text" data-toggle="tooltip" title="<?php echo $tooltipTitle; ?>" name="<?php echo $field; ?>" class="timepicker <?php echo self::getFielsizeClass(); ?>" value="<?php echo $model->$name; ?>" <?php echo $inline; ?>>
            <span class="add-on"><i class="icon-time"></i></span>
        </div>
        <?php
        if (!self::$_clocksInit) {
            self::$_clocksInit = true;
            ?>
            <script>
                jQuery(document).ready(function(){
                    jQuery(".timepicker").timepicker({
                        showMeridian: false,
                        defaultTime: "current"
                    });
                });
            </script>
        <?php
        $fieldRequired = false;
        $validators = CCoreObjectsManager::getFieldValidators($model);
        if (array_key_exists($name, $validators)) {
            $fieldRequired = true;
        }
        if ($fieldRequired) {
            self::requiredStar();
        }
        }
    }
    public static function activeDateField($name, CModel $model, $format = "dd.mm.yyyy", $id = "", $class = "", $html = "") {
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
            $id = $field;
        }
        if ($format == "") {
            $format = "%d.%m.%Y";
        }
        $id = str_replace("[", "_", $id);
        $id = str_replace("]", "_", $id);
        $tooltipTitle = null;
        if (!is_null(CCoreObjectsManager::getCoreModelFieldByFieldName($model, $name))) {
            $tooltipTitle = CCoreObjectsManager::getCoreModelFieldByFieldName($model, $name)->comment;
        }
        $inline = "";
        $inline .= $model->restrictionAttribute();
        if ($html != "") {
            $inline .= " ".$html;
        }
        ?>
        <div class="input-append date <?php echo self::getFielsizeClass(); ?> datepicker" id="<?php echo $id; ?>" data-date="<?php echo $model->$name; ?>" data-toggle="tooltip" title="<?php echo $tooltipTitle; ?>" data-date-format="<?php echo $format; ?>">
            <input name="<?php echo $field; ?>" class="<?php echo self::getFielsizeClass(); ?>" type="text" value="<?php echo $model->$name; ?>" <?php echo $inline; ?>>
            <?php if (!$model->isEditRestriction()) {?>
                <span class="add-on"><i class="icon-th"></i></span>
            <?php }?>
        </div>
        <?php
        if (!self::$_calendarInit) {
            self::$_calendarInit = true;
            ?>
            <script>
                jQuery(document).ready(function(){
                    jQuery(".datepicker").datepicker();
                });
            </script>
            <?php
        }
        $fieldRequired = false;
        $validators = CCoreObjectsManager::getFieldValidators($model);
        if (array_key_exists($name, $validators)) {
            $fieldRequired = true;
        }
        if ($fieldRequired) {
            self::requiredStar();
        }
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
        $tooltipTitle = null;
        if (!is_null(CCoreObjectsManager::getCoreModelFieldByFieldName($model, $name))) {
            $tooltipTitle = CCoreObjectsManager::getCoreModelFieldByFieldName($model, $name)->comment;
        }
        $inline = "";
        $inline .= $model->restrictionAttribute();
        if ($html != "") {
            $inline .= " ".$html;
        }
        self::textBox($field, $model->$name, $id, $class, $inline, $tooltipTitle);
        $fieldRequired = false;
        $validators = CCoreObjectsManager::getFieldValidators($model);
        if (array_key_exists($name, $validators)) {
            $fieldRequired = true;
        }
        if ($fieldRequired) {
            self::requiredStar();
        }
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
     * @param $html
     * @param $control
     */
    public static function label($text, $for, $html = "", $control = false) {
        $inline = "";
        if ($html != "") {
            $inline .= $html;
        }
        if ($control) {
            echo '<label for="'.$for.'" '.$html.'>'.$text.'</label>';
        } else {
            echo '<label for="'.$for.'" class="control-label" "'.$html.'">'.$text.'</label>';
        }
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
        $labels = CCoreObjectsManager::getAttributeLabels($model);
        if (array_key_exists($name, $labels)) {
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
    public static function submit($value, $canChooseContinue = true) {
        if ($canChooseContinue) {
            ?>
            <script>
                jQuery(document).ready(function(){
                    jQuery("#_saveAndContinue").click(function(){
                        var form = jQuery(this).parents("form:first");
                        jQuery("input[name=_continueEdit]").val("1");
                        jQuery(form).submit();
                        return false;
                    });
                    jQuery("#_saveAndBack").click(function(){
                        var form = jQuery(this).parents("form:first");
                        jQuery("input[name=_continueEdit]").val("0");
                        jQuery(form).submit();
                        return false;
                    });
                });
            </script>
            <input type="hidden" name="_continueEdit" value="1">
            <div class="btn-group">
                <button class="btn btn-primary"><?php echo $value; ?></button>
                <button class="btn dropdown-toggle btn-primary" data-toggle="dropdown">
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu">
                    <li>
                        <a href="#" id="_saveAndContinue">Сохранить и продолжить</a>
                    </li>
                    <li>
                        <a href="#" id="_saveAndBack">Сохранить и к списку</a>
                    </li>
                </ul>
            </div>
        <?php
        } else {
            echo '<button type="submit" class="btn btn-primary">'.$value.'</button>';
        }
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
     * @param string $tooltipTitle
     */
    public static function textBox($name, $value = null, $id = "", $class = "", $html = "", $tooltipTitle = null) {
        if ($id == "") {
            $id = $name;
        }
        $inline = "";
        if ($id != "") {
            $inline .= ' id="'.$id.'"';
        }
        if ($class == "") {
            $class = self::getFielsizeClass();
        }
        if ($class != "") {
            $inline .= ' class="'.$class.'" ';
        }
        if ($html == "") {
            $html = ' rows="5"';
        }
        if ($html != "") {
            $inline .= $html;
        }
        echo '<textarea data-toggle="tooltip" title="'.$tooltipTitle.'" name="'.$name.'" '.$inline.'>'.$value.'</textarea>';
    }
    public static function checkBox($name, $value, $checked = false, $id = "", $class = "", $html = "", $tooltipTitle = null) {
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
        echo '<input type="hidden" name="'.$name.'" value="0">';
        echo '<input type="checkbox" data-toggle="tooltip" title="'.$tooltipTitle.'" name="'.$name.'" '.$checked.' '.$inline.'>';
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
        $tooltipTitle = null;
        if (!is_null(CCoreObjectsManager::getCoreModelFieldByFieldName($model, $name))) {
            $tooltipTitle = CCoreObjectsManager::getCoreModelFieldByFieldName($model, $name)->comment;
        }
        foreach ($values as $key=>$value) {
            $inputName = $model::getClassName();
            if ($submodelName !== "") {
                $inputName .= "[".$submodelName."]";
            }
            $inputName .= "[".$name."][]";
            echo '<label class="checkbox">';
            echo '<input type="checkbox" data-toggle="tooltip" title="'.$tooltipTitle.'" name="'.$inputName.'"';
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
            echo ' value="'.$key.'">'.$value;
            echo '</label>';
        }
    }
    public static function actionUserRolesSelector($name, CModel $model) {
        $values = CStaffManager::getAllUserRolesList();
        $selectValues = CStaffManager::getAccessLevelsList();
        $selectValues[0] = " -- Нет доступа --";
        $submodelName = "";
        if (strpos($name, "[") !== false) {
            $submodelName = substr($name, 0, strpos($name, "["));
            $name = substr($name, strpos($name, "[") + 1);
            $name = substr($name, 0, strlen($name) - 1);
            $model = $model->$submodelName;
        }
        $roles = $model->$name;
        foreach ($values as $key=>$value) {
            $level = 0;
            $hasRole = false;
            if ($roles->hasElement($key)) {
                $hasRole = true;
                $level = ACCESS_LEVEL_READ_OWN_ONLY;
                $role = $roles->getItem($key);
                /**
                 * Конкретному пользователю можно запретить
                 * доступ к конкретной задаче
                 *
                if ($role->level > 0) {
                    $level = $role->level;
                }
                 */
                $level = $role->level;
            }
            //
            $inputName = $model::getClassName();
            if ($submodelName !== "") {
                $inputName .= "[".$submodelName."]";
            }
            $inputName .= "[".$name."][".$key."]";
            echo '<div class="control-group form-inline">';
            echo '<label class="control-label">'.$value.'</label>';
            echo '<div class="controls">';
            echo '<select name="'.$inputName.'" asu-attr="role_'.$key.'">';
            foreach ($selectValues as $selectKey=>$selectValue) {
                echo '<option value="'.$selectKey.'" ';
                if ($level == $selectKey) {
                    echo 'selected';
                }
                echo '>'.$selectValue.'</option>';
            }
            echo '</select>';
            if (get_class($model) == "CUser") {
                echo '&nbsp;<label class="checkbox">';
            	echo '<input type="checkbox" class="roleDisabler" asu-attr="disabler_'.$key.'" ';
            	if (!$hasRole) {
                	echo 'checked';
            	}
            	echo ' value="'.$key.'">Наследовать из групп';
            	echo '</label>';
            }
            echo '</div>';
            echo '</div>';
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
        $tooltipTitle = null;
        if (!is_null(CCoreObjectsManager::getCoreModelFieldByFieldName($model, $name))) {
            $tooltipTitle = CCoreObjectsManager::getCoreModelFieldByFieldName($model, $name)->comment;
        }
        $inline = "";
        if ($name != "_edit_restriction") {
            $inline .= $model->restrictionAttribute();
        }
        if ($html != "") {
            $inline .= " ".$html;
        }
        if ($model->$name == true) {
            $name = $model::getClassName()."[".$name."]";
            self::checkBox($name, "1", true, $id, $class, $inline, $tooltipTitle);
        } else {
            $name = $model::getClassName()."[".$name."]";
            self::checkBox($name, "1", false, $id, $class, $inline, $tooltipTitle);
        }
    }
    public static function error($name, CModel $model) {
        if ($model->getValidationErrors()->hasElement($name)) {
            echo '<span class="help-inline">'.$model->getValidationErrors()->getItem($name)."</span>";
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
        $tooltipTitle = null;
        if (!is_null(CCoreObjectsManager::getCoreModelFieldByFieldName($model, $name))) {
            $tooltipTitle = CCoreObjectsManager::getCoreModelFieldByFieldName($model, $name)->comment;
        }
        // дописываем скрипт для мультивыбора
        if (!self::$_multiselectInit) {
            echo '
                <script>
                    jQuery(document).ready(function(){
                        jQuery(".multiselectClonable").change(function(){
                            var current = jQuery(this);
                            var span = jQuery(current).parent();
                            var parent = jQuery(span).parent();
                            // клонируем текущий элемент
                            jQuery(span).clone(true).appendTo(parent);
                            // у текущего элемента активируем удалялку и снимаем класс клонирования
                            var img = jQuery(span).find("img")[0];
                            jQuery(img).css("display", "");
                            jQuery(current).removeClass("multiselectClonable");
                            jQuery(current).unbind("change");
                        });
                    });
                </script>
                ';
            self::$_multiselectInit = true;
        }
        echo '<div style="margin-left: 200px; ">';
        foreach ($model->$name->getItems() as $f) {
            // отрисовываем поле со значением
            echo '<span>';
            self::dropDownList($field, $values, $f->getId(), "", "", "", $tooltipTitle);
            echo '&nbsp;&nbsp; <img src="'.WEB_ROOT.'images/design/mn.gif" style="cursor: pointer; " onclick="jQuery(this).parent().remove(); return false;" />';
            echo '<br /></span>';
        }
        // добавляем последний невыбранным
        echo '<span>';
        self::dropDownList($field, $values, null, "", "multiselectClonable");
        echo '&nbsp;&nbsp; <img src="'.WEB_ROOT.'images/design/mn.gif" style="cursor: pointer; display: none; " onclick="jQuery(this).parent().remove(); return false;" />';
        echo '<br /></span>';
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
        echo '<div class="pagination"><ul>';
        $requestVariables = CRequest::getGlobalRequestVariables();
        $toCheck = 1;
        // какую страницу отметить по умолчанию
        if ($requestVariables->hasElement("page")) {
            $toCheck = $requestVariables->getItem("page");
            $requestVariables->removeItem("page");
            $requestVariables->removeItem("page_size");
        }
        foreach ($paginator->getPagesList($action) as $page=>$link) {
            // добавляем параметры в запрос
            foreach ($requestVariables->getItems() as $key=>$value) {
                $link .= "&".$key."=".$value;
            }
            if ($toCheck == $page) {
                echo '<li class="active"><a href="'.$link.'">'.$page.'</a></li>';
            } else {
                echo '<li><a href="'.$link.'">'.$page.'</a></li>';
            }
        }
        echo '</ul>';
        echo '</div>';
        echo '<span>Текущая страница: '.$paginator->getCurrentPageNumber().' </span>';
        echo '<span>Всего: '.$paginator->getPagesCount().' </span>';
        echo '<span>Отображать: ';
        $sizes = $paginator->getPageSizes();
        echo '<div class="btn-group">';
        echo '<a class="btn btn-small dropdown-toggle" data-toggle="dropdown" data-target="#">';
        echo $sizes[$paginator->getCurrentPageSize()];
        echo '<span class="caret"></span>';
        echo '</a>';
        echo '<ul class="dropdown-menu">';
        // добавляем другие параметры, которые есть в запросе
        foreach ($requestVariables->getItems() as $key=>$value) {
            $action .= "&".$key."=".$value;
        }
        foreach ($sizes as $key=>$name) {
            echo '<li>';
            echo '<a href="'.$action.'&page_size='.$key.'">'.$sizes[$key].'</a>';
            echo '</li>';
        }
        echo '</ul>';
        echo '</div>';
        echo '<span>';
    }
    /**
     * Модальное окно
     * 
     * @param string $id
     * @param string $header
     * @param string $content
     * @return string
     */
    public static function modalWindow($id, $header, $content) {
        echo '
    		<div id="'.$id.'" class="modal hide fade">
    			<div class="modal-header">
    				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    				<h3>'.$header.'</h3>
    			</div>
    			<div class="modal-body">
    				'.$content.'
        		</div>
        		<div class="modal-footer">
        			<button class="btn" data-dismiss="modal" aria-hidden="true">Закрыть</button>
        		</div>
        	</div>
        ';
    }
    public static function helpForCurrentPage() {
        if (!is_null(CHelpManager::getHelpForCurrentPage())) {
            $content = CHelpManager::getHelpForCurrentPage()->content;
            echo '<div class="alert alert-info">';
            echo '<h4>'.CHelpManager::getHelpForCurrentPage()->title.'</h4>';
            $printHelpBox = false;
            $wikiHelp = false;
            if (mb_strlen($content) > 512) {
                $symbols = 512; // Количество символов которые надо вывести
                $text = "";
                $words = explode(" ", $content);
                for ($i=0; $i<count($words); $i++) {
                	$nv_str=$text.$words[$i]." ";
                	if(strlen($nv_str)<$symbols){
                		$text = $nv_str;
                	}
                	else {
                		break;
                	}
                }
                echo $text."...";
                echo '<p><a href="#help" data-toggle="modal">Читать полностью</a></p>';
                $printHelpBox = true;
            } else {
                echo $content;
            }
            if (CHelpManager::getHelpForCurrentPage()->wiki_url != "") {
                echo '<p><a href="#wikiHelp" data-toggle="modal">Справка из Википедии</a></p>';
                $wikiHelp = true;
            }
            if (CSession::getCurrentUser()->hasRole("help_add_inline")) {
                echo '<p>';
                echo '<a href="'.WEB_ROOT.'_modules/_help/index.php?action=edit&id='.CHelpManager::getHelpForCurrentPage()->getId().'" target="_blank">Редактировать справку</a>';
                echo '</p>';
            }
            echo '</div>';
            if ($printHelpBox) {
                self::modalWindow("help", "Справка", $content);
            }
            if ($wikiHelp) {
            	CHelpManager::getWikiAddressModalWindow(CHelpManager::getHelpForCurrentPage()->wiki_url);
            }
        } elseif (CSession::getCurrentUser()->hasRole("help_add_inline")) {
            echo '<div class="alert alert-info">';
            $uri = "";
            if (array_key_exists("REQUEST_URI", $_SERVER)) {
                $uri = $_SERVER["REQUEST_URI"];
                $uri = str_replace(ROOT_FOLDER, "", $uri);
            }
            echo '<a href="'.WEB_ROOT.'_modules/_help/index.php?action=add&page='.$uri.'" target="_blank">Добавить справку для текущей страницы</a>';
            echo '</div>';
        }
    }
    public static function warningSummary(CModel $model) {
        $model->validateModel(VALIDATION_EVENT_READ);
        if ($model->getValidationWarnings()->getCount() > 0) {
            echo '<div class="alert">';
            foreach ($model->getValidationWarnings()->getItems() as $warning) {
                echo "<p>".$warning."</p>";
            }
            echo '</div>';
        }
    }
    public static function errorSummary(CModel $model) {
        if ($model->getValidationErrors()->getCount() > 0) {
            echo '<div class="alert alert-error">';
            foreach ($model->getValidationErrors()->getItems() as $error) {
                echo "<p>".$error."</p>";
            }
            echo '</div>';
        }
    }
    public static function activeUpload($name, CModel $model, $isMultiple = false, $imageWidth = 200) {
        $submodelName = "";
        if (strpos($name, "[") !== false) {
            $submodelName = substr($name, 0, strpos($name, "["));
            $name = CUtils::strRight($name, "[");
            $name = CUtils::strLeft($name, "]");
            $model = $model->$submodelName;
        }
        $field = $model::getClassName();
        if ($submodelName !== "") {
            $field .= "[".$submodelName."]";
        }
        $field .= "[".$name."]";
        if ($isMultiple) {
            $field .= "[]";
        }
        $data = $model->$name;
        $inline = "";
        $class = self::getFielsizeClass();
        $inline .= ' class="'.$class.'"';
        $uploadDir = "";
        if (array_key_exists($name, $model->fieldsProperty())) {
            $properties = $model->fieldsProperty();
            $property = $properties[$name];
            if ($property["type"] == FIELD_UPLOADABLE) {
                $uploadDir = $property["upload_dir"];
            }
        }
        $tooltipTitle = null;
        if (!is_null(CCoreObjectsManager::getCoreModelFieldByFieldName($model, $name))) {
            $tooltipTitle = CCoreObjectsManager::getCoreModelFieldByFieldName($model, $name)->comment;
        }
        ?>
        <div class="activeUpload" asu-storage="<?php echo $uploadDir; ?>" asu-multiple="<?php echo ($isMultiple) ? "true":"false" ;?>" asu-size="<?php echo $imageWidth; ?>" asu-value-name="<?php echo $field; ?>">

            <?php if (is_object($data)) : ?>
                <?php foreach ($data->getItems() as $val) : ?>
                    <input type="hidden" name="<?php echo $field; ?>" value="<?php echo $val->getId(); ?>" asu-type="value">
                <?php endforeach; ?>
            <?php else: ?>
                <input type="hidden" name="<?php echo $field; ?>" value="<?php echo $data; ?>" asu-type="value">
            <?php endif;?>

            <table style="margin-left: 0px; " border="0" <?php echo $inline; ?>>
                <tbody>
                <tr>
                    <td width="100%">
                        <input type="file" data-toggle="tooltip" title="<?php echo $tooltipTitle; ?>" name="upload_<?php echo $name; ?>" asu-type="upload" />
                    </td>
                    <td style="width: 16px; " valign="top">
                        <i class="icon-remove" />
                    </td>
                </tr>
                <tr>
                    <td width="100%">
                        <div class="btn-group btn-group-vertical" asu-type="placeholder" style="width: 100%; ">

                        </div>
                    </td>
                    <td style="width: 16px; ">

                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <?php
        if (!self::$_uploadWidgetInit) {
            self::$_uploadWidgetInit = true;
            ?>
            <script>
                jQuery(document).ready(function(){
                    jQuery(".activeUpload").activeUpload();
                });
            </script>
            <?php
        }
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
                if ($variables["id"] != "selectedInView") {
                    $url = "?action=print".
                        "&manager=".$variables['manager'].
                        "&method=".$variables['method'].
                        "&id=".$variables['id'].
                        "&template=".$form->getId();
                    echo '<li><a href="'.WEB_ROOT.'_modules/_print/'.$url.'" target="_blank">'.$form->title.'</a></li>';
                } else {
                    if (!self::$_printFormViewInit) {
                        self::$_printFormViewInit = true;
                        ?>
						<script>
							function printTemplateFromView(baseUrl){
								var selected = new Array();
								jQuery.each(jQuery("input[name='selectedDoc[]']:checked"), function(key, value){
									selected.push(jQuery(value).val());
								});
								if (selected.length == 0) {
									alert("Выберите один или несколько документов");
									return false;
								}
								var ids = new Array();
								jQuery.each(selected, function(key, value){
									ids[ids.length] = value;
								});
								jQuery("#printDialog").modal("hide");
								window.location.href = web_root + "_modules/_print/" + baseUrl + "&id=" + ids.join(":");
							}
						</script>
                        <?php
                    }
                    $url = "?action=print".
                        "&manager=".$variables['manager'].
                        "&method=".$variables['method'].
                        "&template=".$form->getId();
                    echo '<li><a href="#" onclick="printTemplateFromView(\''.$url.'\'); return false;">'.$form->title.'</a></li>';;
                }
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
    public static function printGroupOnTemplate($template, $selectedDoc = false, $url = null, $action = null, $id = null) {
        $formset = CPrintManager::getFormset($template);
        if (!is_null($formset)) {
            $forms = $formset->activeForms;
            $variables = $formset->computeTemplateVariables();
            echo "<ul>";
            foreach ($forms->getItems() as $form) {
            	if ($variables['manager'] == "CWorkPlanManager") {
            		echo '<li><a href="#" onclick="printWithTemplateWorkplans(';
            	} else {
            		echo '<li><a href="#" onclick="printWithTemplate(';
            	}
                echo "'".$variables['manager']."'";
                echo ", '".$variables['method']."'";
                echo ", '".$form->getId()."'";
                echo ", '".$selectedDoc."'";
                echo ", '".$url."'";
                echo ", '".$action."'";
                echo ", '".$id."'";
                echo '); return false;">'.$form->title.'</a></li>';
            }
            echo "</ul>";
        }
    }
    /**
     * Заголовок таблицы с возможностью сортировки
     * 
     * @param $field - название поля модели
     * @param CModel $model - модель
     * @param string $manualSort - возможность ручной сортировки при отключении глобального поиска и глобальных сортировок в CRecordSet
     * @param string $allowSort - позволять сортировку поля, false - если не нужна ссылка для сортировки в заголовке
     * @return string
     */
    public static function tableOrder($field, CModel $model = null, $manualSort = false, $allowSort = true) {
        if (is_null($model)) {
            return "";
        }
        $labels = CCoreObjectsManager::getAttributeLabels($model);
        $columnLabels = CCoreObjectsManager::getAttributeTableLabels($model);
        if (array_key_exists($field, $columnLabels)) {
        	$label = $columnLabels[$field];
        } elseif (array_key_exists($field, $labels)) {
            $label = $labels[$field];
        } else {
            $label = $field;
        }
        $exclude = array(
            'order',
            'direction'
        );
        foreach (CRequest::getGlobalRequestVariables()->getItems() as $key=>$value) {
            if (!in_array($key, $exclude)) {
                if (is_scalar($value)) {
                    $actions[] = $key."=".$value;
                }
            }
        }
        /**
         * Позволяем сортировать только если поле есть в модели
         */
        $showLink = $manualSort;
        if (is_a($model, "CActiveModel")) {
            if ($model->getDbTableFields()->hasElement($field)) {
                $showLink = true;
            }
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
    	} else {
    		$actions[] = "direction=desc";
    	}
        if ($showLink and $allowSort) {
            $label = '<a href="'.CUtils::getScriptName().'?'.implode($actions, "&").'">'.$label.'</a>';
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
    public static function activeLookup($name, CModel $model, $catalog = "", $isMultiple = false, $properties = array(), $allowCreation = false) {
        /**
         * Безумно полезная штука для работы со связанными
         * моделями. Если в названии поля есть скобки, то производится
         * разбор вида подмодель[ее поле]
         */
        $submodelName = "";
        if (strpos($name, "[") !== false) {
            $submodelName = substr($name, 0, strpos($name, "["));
            $name = CUtils::strRight($name, "[");
            $name = CUtils::strLeft($name, "]");
            $model = $model->$submodelName;
        }
        $field = $model::getClassName();
        if ($submodelName !== "") {
            $field .= "[".$submodelName."]";
        }
        $field .= "[".$name."]";
        $fieldRequired = false;
        $validators = CCoreObjectsManager::getFieldValidators($model);
        if (array_key_exists($name, $validators)) {
            $fieldRequired = true;
        }
        $inline = "";
        $class = self::getFielsizeClass();
        $inline .= ' class="'.$class.'"';
        $data = $model->$name;
        $tooltipTitle = null;
        if (!is_null(CCoreObjectsManager::getCoreModelFieldByFieldName($model, $name))) {
            $tooltipTitle = CCoreObjectsManager::getCoreModelFieldByFieldName($model, $name)->comment;
        }
        ?>
        <div class="catalogLookup" asu-catalog="<?php echo $catalog; ?>" asu-multiple="<?php echo ($isMultiple) ? "true":"false" ;?>" asu-value-name="<?php echo $field; ?>" asu-creation="<?php echo ($allowCreation) ? "true":"false" ;?>">

        <?php if (is_object($data)) : ?>
            <?php $index = 0; ?>
            <?php foreach ($data->getItems() as $val) : $index++; ?>
                <input type="hidden" name="<?php echo $field; ?>[<?php echo $index; ?>]" value="<?php echo $val->getId(); ?>" asu-type="value">
            <?php endforeach; ?>
        <?php else: ?>
            <input type="hidden" name="<?php echo $field; ?>" value="<?php echo $data; ?>" asu-type="value">
        <?php endif;?>
		<?php foreach ($properties as $key=>$value) : ?>
			<input type="hidden" value="<?php echo $value; ?>" asu-type="property" asu-property-key="<?php echo $key; ?>">
		<?php endforeach; ?>

        <table <?php echo $inline; ?> id="<?php echo $name;?>" style="margin-left: 0px; ">
            <tr>
                <td width="100%">
                    <input type="text" data-toggle="tooltip" title="<?php echo $tooltipTitle; ?>" value="" asu-name="lookup" placeholder="Введите текст для поиска" style="width: 95%; " <?php echo $model->restrictionAttribute(); ?>>
                </td>
                <td style="width: 16px; ">
                    <?php if (!$model->isEditRestriction()) {?>
                    <i class="icon-search" />
                    <?php }?>
                </td>
            </tr>
            <tr>
                <td width="100%">
                    <div class="btn-group btn-group-vertical" asu-type="placeholder" style="width: 100%; ">

                    </div>
                </td>
                <td style="width: 16px; ">
                    <?php if (!$model->isEditRestriction()) {?>
                    <i class="icon-remove" />
                    <?php }?>
                </td>
                
            </tr>
        </table>
        </div>
        <?php
        if (!self::$_catalogLookupInit) {
            self::$_catalogLookupInit = true;
            ?>
            <script>
                jQuery(document).ready(function(){
                    jQuery(".catalogLookup").catalogLookup();
                });
            </script>
            <?php
        }
        if ($fieldRequired) {
            self::requiredStar();
        }
    }
    /**
     * Предпросмотр вложений со ссылками на оригиналы
     *
     * @param $name
     * @param CModel $model
     * @param int $size
     * @param bool $addLinkToOriginal
     */
    public static function activeAttachPreview($name, CModel $model, $addLinkToOriginal = false, $size = 100) {
        $attributes = $model->fieldsProperty();
        $display = false;
        if (array_key_exists($name, $attributes)) {
            $field = $attributes[$name];
            if ($field["type"] == FIELD_UPLOADABLE) {
                $storage = $field["upload_dir"];
                $file = $model->$name;
                if ($file !== "") {
                    if (file_exists($storage.$file)) {
                        $display = true;
                    }
                }
            }
        }
        if ($display) {
            // заменяем обратный слэш в адресе на прямой
            $linkWithBackSlash = CUtils::strRight($storage, CORE_CWD).$file;
            $link = str_replace('\\', '/', $linkWithBackSlash);
            $icon = "";
            if (CUtils::isImage($storage.$file)) {
                // показываем превью изображения
                $icon = WEB_ROOT."_modules/_thumbnails/?src=".$link."&w=".$size;
            } else {
                // показываем значок типа документа
                $filetype = CUtils::getMimetype($storage.$file);
                if (file_exists(CORE_CWD.CORE_DS."images".CORE_DS.ICON_THEME.CORE_DS."64x64".CORE_DS."mimetypes".CORE_DS.$filetype.".png")) {
                    $icon = WEB_ROOT."images/".ICON_THEME."/64x64/mimetypes/".$filetype.".png";
                } else {
                    $icon = WEB_ROOT."images/".ICON_THEME."/64x64/mimetypes/unknown.png";
                }
            }
            if ($addLinkToOriginal) {
                echo '<a href="'.WEB_ROOT.''.$link.'" target="_blank"';
                if (CUtils::isImage($storage.$file)) {
                	// прочие типы изображений не открываем во всплывающем окне (например, формат .tif)
                    if (in_array(end(explode(".", $file)), array("gif","jpg","jpeg","png","bmp"))) {
                        echo ' class="image_clearboxy"';
                    }
                }
                echo '>';
            }
            echo '<img src="'.$icon.'" />';
            if ($addLinkToOriginal) {
                echo '</a>';
            }
        }
        if (!self::$_clearboxInit) {
            self::$_clearboxInit = true;
            ?>
            <script>
                jQuery(document).ready(function(){
                    jQuery("a.image_clearboxy").colorbox({
                        maxHeight: "100%",
                        title: function(){
                            var url = $(this).attr('href');
                            return '<a href="' + url + '" target="_blank">Открыть в полном размере</a>';
                        }
                    });
                });
            </script>
            <?php
        }
    }
    /**
     * Показать правое меню действий
     *
     * @param array $items
     */
    public static function displayActionsMenu(array $items) {
        /**
         * Делегируем обязанности другому классу, так как
         * этот уже достаточно вырос
         */
        $renderer = new CActionsMenuRenderer();
        $renderer->render($items);
    }
    public static function activeViewGroupSelect($name, CModel $model, $isHeader = false, $isDoc = false) {
        if ($isHeader) {
            // это в шапке таблицы, тут нужно показать групповую скрывалку/показывалку
            if (!self::$_viewGroupSelectInit) {
                self::$_viewGroupSelectInit = true;
                ?>
                <script>
                    jQuery(document).ready(function(){
                        jQuery("._viewGroupSelector").on("change", function(){
                            var index = jQuery(this).attr("asu-index");
                            var items = jQuery("._viewGroupSelectorItem[asu-index=" + index + "]");
                            if (jQuery(this).is(":checked")) {
                                for (var i = 0; i < items.length; i++) {
                                    jQuery(items[i]).attr("checked", true);
                                }
                            } else {
                                for (var i = 0; i < items.length; i++) {
                                    jQuery(items[i]).attr("checked", false);
                                }
                            }
                        });
                    });
                </script>
                <?php
            }
            self::$_widgetsIndex++;
            echo '<input type="checkbox" value="1" class="_viewGroupSelector" asu-index="'.(self::$_widgetsIndex).'" />';
        } elseif($isDoc) {
            $data = $model->$name;
            echo '<input type="checkbox" name="selectedDoc[]" value="'.$data.'" class="_viewGroupSelectorItem" asu-index="'.(self::$_widgetsIndex).'" />';
        } else {
            $data = $model->$name;
            echo '<input type="checkbox" name="selectedInView[]" value="'.$data.'" class="_viewGroupSelectorItem" asu-index="'.(self::$_widgetsIndex).'" />';
        }
    }
    /**
     * Групповое выделение чекбоксов с произвольными значениями $value
     * 
     * @param string $value
     * @param boolean $isHeader
     */
    public static function checkboxGroupSelect($value, $isHeader = false) {
    	if ($isHeader) {
    		// это в шапке таблицы, тут нужно показать групповую скрывалку/показывалку
            if (!self::$_viewGroupSelectInit) {
    			self::$_viewGroupSelectInit = true;
    			?>
                    <script>
                        jQuery(document).ready(function(){
                            jQuery("._checkboxGroupSelector").on("change", function(){
                                var index = jQuery(this).attr("asu-index");
                                var items = jQuery("._checkboxGroupSelectorItem[asu-index=" + index + "]");
                                if (jQuery(this).is(":checked")) {
                                    for (var i = 0; i < items.length; i++) {
                                        jQuery(items[i]).attr("checked", true);
                                    }
                                } else {
                                    for (var i = 0; i < items.length; i++) {
                                        jQuery(items[i]).attr("checked", false);
                                    }
                                }
                            });
                        });
                    </script>
                    <?php
            }
            self::$_widgetsIndex++;
            echo '<input type="checkbox" value="1" class="_checkboxGroupSelector" asu-index="'.(self::$_widgetsIndex).'" />';
        } else {
            echo '<input type="checkbox" name="selectedDoc[]" value="'.$value.'" class="_checkboxGroupSelectorItem" asu-index="'.(self::$_widgetsIndex).'" />';
        }
    }

    /**
     * Самодостаточный аяксовый компонент
     *
     * @param string $url
     * @param CModel $model
     */
    public static function activeComponent($controllerUrl = "", CModel $model, $params = array()) {
        /**
         * По умолчанию грузим содержимое действия index, если не указано иначе
         */
        $defaultAction = "index";
        if (array_key_exists("defaultAction", $params)) {
            $defaultAction = $params["defaultAction"];
        }
        $withoutScripts = false;
        if (array_key_exists("withoutScripts", $params)) {
        	$withoutScripts = $params["withoutScripts"];
        }
        self::$_widgetsIndex++;
        echo '<div class="asu_component" id="component_'.(self::$_widgetsIndex).'" asu-controller="'.$controllerUrl.'" asu-action="'.$defaultAction.'" asu-withoutScripts="'.$withoutScripts.'" asu-type="component" asu-index="'.self::$_widgetsIndex.'"></div>';

        if (!self::$_componentsInit) {
            self::$_componentsInit = true;
        ?>
            <script>
                jQuery(document).ready(function(){
                    jQuery("[asu-type='component']").components();
                });
            </script>
        <?php
        }
    }
}