<?php
/**
 * Created by PhpStorm.
 * User: abarmin
 * Date: 14.03.15
 * Time: 21:18
 */

class NgHtml extends CHtml{
    private static $select2Init = false;

    private static function rowStart(CModel $model, $ngFieldName) {
        echo '<div class="control-group">';
        if ($ngFieldName != "") {
            CHtml::activeLabel($ngFieldName, $model);
        }
        echo '<div class="controls">';
    }
    private static function rowEnd(CModel $model) {
        echo '</div>';
        echo '</div>';
    }
    public static function activeText($ngModelName, $ngFieldName, $properties = array()) {
        $html = "";
        if (array_key_exists("html", $properties)) {
            $html = $properties["html"];
        }
        $html .= ' ng-model="'.$ngModelName.'.'.$ngFieldName.'"';
        CHtml::textField(
            $ngModelName."_".$ngFieldName,
            null,
            "",
            "",
            $html);
    }
    public static function activeTextRow(CModel $model, $ngModelName, $ngFieldName) {
        self::rowStart($model, $ngFieldName);
        self::activeText($ngModelName, $ngFieldName);
        self::rowEnd($model);
    }
    public static function activeTextBox($ngModelName, $ngFieldName, $properties = array()) {
        $html = "";
        if (array_key_exists("html", $properties)) {
            $html = $properties["html"];
        }
        CHtml::textBox(
            $ngModelName."_".$ngFieldName,
            null,
            "",
            "",
            'ng-model="'.$ngModelName.'.'.$ngFieldName.'" '.$html);
    }
    public static function activeTextBoxRow(CModel $model, $ngModelName, $ngFieldName) {
        self::rowStart($model, $ngFieldName);
        self::activeTextBox($ngModelName, $ngFieldName);
        self::rowEnd($model);
    }
    public static function activeTextTagging($ngModelName, $ngFieldName, $properties = array()) {
        $glossary = "emptyGlossary";
        if (array_key_exists("glossary", $properties)) {
            $glossary = $properties["glossary"];
        }
        $multiple = false;
        if (array_key_exists("multiple", $properties)) {
            $multiple = $properties["multiple"];
        }
        $glossaryProperties = array();
        if (array_key_exists("properties", $properties)) {
            $glossaryProperties = $properties["properties"];
        }
        if (count($glossaryProperties) > 0) {
            echo '<script>
                lookupCatalogProperties.'.$glossary.' = '.json_encode($glossaryProperties).';
            </script>';
        }
        echo '<div ng-controller="LookupController as lookupCtrl" ng-init="lookupCtrl.initLookup(\''.$glossary.'\')">';

        if ($multiple) {
            echo '<ui-select tagging tagging-label="Нажмите Enter для добавления нового значения" style="width: 312px;" multiple ng-model="'.$ngModelName.'.'.$ngFieldName.'" theme="select2">';
            echo '<ui-select-match placeholder="Выберите значение из списка">{{$item}}</ui-select-match>';
            echo '<ui-select-choices repeat="item in itemsPlain | filter: $select.search ">';
            echo '<div ng-bind-html="item | highlight: $select.search"></div>';
            echo '</ui-select-choices>';
            echo '</ui-select>';
        } else {
            echo '<ui-select tagging tagging-label="Нажмите Enter для добавления нового значения" style="width: 312px;" ng-model="'.$ngModelName.'.'.$ngFieldName.'" theme="select2">';
            echo '<ui-select-match placeholder="Выберите значение из списка"><span ng-bind="'.$ngModelName.'.'.$ngFieldName.'"/></ui-select-match>';
            echo '<ui-select-choices repeat="item in itemsPlain | filter: $select.search ">';
            echo '<div ng-bind-html="item | highlight: $select.search"></div>';
            echo '</ui-select-choices>';
            echo '</ui-select>';
        }

        echo '</div>';
        // будут по умолчанию на select2
        if (!self::$select2Init) {
            self::$select2Init = true;
            ?>
            <script src="<?php echo WEB_ROOT; ?>_core/_webapp/lookupController.js"></script>
            <style>
                .select2 > .select2-choice.ui-select-match {
                    /* Because of the inclusion of Bootstrap */
                    height: 29px;
                }
            </style>
            <script>
                if (typeof lookupCatalogProperties == "undefined") {
                    lookupCatalogProperties = {};
                }
            </script>
        <?
        }
    }
    public static function activeTagging($ngModelName, $ngFieldName, $properties = array()) {
        $glossary = "emptyGlossary";
        if (array_key_exists("glossary", $properties)) {
            $glossary = $properties["glossary"];
        }
        $multiple = false;
        if (array_key_exists("multiple", $properties)) {
            $multiple = $properties["multiple"];
        }
        $glossaryProperties = array();
        if (array_key_exists("properties", $properties)) {
            $glossaryProperties = $properties["properties"];
        }
        if (count($glossaryProperties) > 0) {
            echo '<script>
                lookupCatalogProperties.'.$glossary.' = '.json_encode($glossaryProperties).';
            </script>';
        }
        echo '<div ng-controller="LookupController as lookupCtrl" ng-init="lookupCtrl.initLookup(\''.$glossary.'\')">';

        if ($multiple) {
            echo '<ui-select tagging tagging-label="Нажмите Enter для добавления нового значения" style="width: 312px;" multiple ng-model="'.$ngModelName.'.'.$ngFieldName.'" theme="select2">';
            echo '<ui-select-match placeholder="Выберите значение из списка">{{$item}}</ui-select-match>';
            echo '<ui-select-choices repeat="item in itemsPlain | filter: $select.search ">';
            echo '<div ng-bind-html="item | highlight: $select.search"></div>';
            echo '</ui-select-choices>';
            echo '</ui-select>';
        } else {
            echo 1234;
        }

        echo '</div>';
        // будут по умолчанию на select2
        if (!self::$select2Init) {
            self::$select2Init = true;
            ?>
            <script src="<?php echo WEB_ROOT; ?>_core/_webapp/lookupController.js"></script>
            <style>
                .select2 > .select2-choice.ui-select-match {
                    /* Because of the inclusion of Bootstrap */
                    height: 29px;
                }
            </style>
            <script>
                if (typeof lookupCatalogProperties == "undefined") {
                    lookupCatalogProperties = {};
                }
            </script>
        <?
        }
    }
    public static function activeSaveRow(CModel $model, $ngModelName, $ngFieldName = "") {
        self::rowStart($model, $ngFieldName);
        echo '<span ng-click="save()" class="btn btn-primary">Сохранить</span>';
        self::rowEnd($model);
    }
    public static function activeTaggingRow(CModel $model, $ngModelName, $ngFieldName, $properties = array()) {
        self::rowStart($model, $ngFieldName);
        self::activeTagging($ngModelName, $ngFieldName, $properties);
        self::rowEnd($model);
    }
    public static function activeSelect($ngModelName, $ngFieldName, $properties = array()) {
        $glossary = "emptyGlossary";
        if (array_key_exists("glossary", $properties)) {
            $glossary = $properties["glossary"];
        }
        $multiple = false;
        if (array_key_exists("multiple", $properties)) {
            $multiple = $properties["multiple"];
        }
        $glossaryProperties = array();
        if (array_key_exists("properties", $properties)) {
            $glossaryProperties = $properties["properties"];
        }
        if (count($glossaryProperties) > 0) {
            echo '<script>
                lookupCatalogProperties.'.$glossary.' = '.json_encode($glossaryProperties).';
            </script>';
        }
        echo '<div ng-controller="LookupController as lookupCtrl" ng-init="lookupCtrl.initLookup(\''.$glossary.'\')">';

        if ($multiple) {
            echo '<ui-select style="width: 312px;" multiple ng-model="'.$ngModelName.'.'.$ngFieldName.'" theme="select2">';
            echo '<ui-select-match placeholder="Выберите значение из списка">{{$item.name}}</ui-select-match>';
            echo '<ui-select-choices repeat="item in items | filter: $select.search ">';
            echo '<div ng-bind-html="item.name | highlight: $select.search"></div>';
            echo '</ui-select-choices>';
            echo '</ui-select>';
        } else {
            echo '<ui-select style="width: 312px;" ng-model="'.$ngModelName.'.'.$ngFieldName.'" theme="select2">';
            echo '<ui-select-match placeholder="Выберите значение из списка">{{$select.selected.name}}</ui-select-match>';
            echo '<ui-select-choices repeat="item.id as item in items | filter: $select.search">';
            echo '<div ng-bind-html="item.name | highlight: $select.search"></div>';
            echo '</ui-select-choices>';
            echo '</ui-select>';
        }

        echo '</div>';
        // будут по умолчанию на select2
        if (!self::$select2Init) {
            self::$select2Init = true;
            ?>
            <script src="<?php echo WEB_ROOT; ?>_core/_webapp/lookupController.js"></script>
            <style>
                .select2 > .select2-choice.ui-select-match {
                    /* Because of the inclusion of Bootstrap */
                    height: 29px;
                }
            </style>
            <script>
                if (typeof lookupCatalogProperties == "undefined") {
                    lookupCatalogProperties = {};
                }
            </script>
        <?
        }
    }
    public static function activeSelectRow(CModel $model, $ngModelName, $ngFieldName, $properties = array()) {
        self::rowStart($model, $ngFieldName);
        self::activeSelect($ngModelName, $ngFieldName, $properties);
        self::rowEnd($model);

    }
}