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
        CHtml::activeLabel($ngFieldName, $model);
        echo '<div class="controls">';
    }
    private static function rowEnd(CModel $model) {
        echo '</div>';
        echo '</div>';
    }
    public static function activeTextRow(CModel $model, $ngModelName, $ngFieldName) {
        self::rowStart($model, $ngFieldName);
        CHtml::textField(
            $ngModelName."_".$ngFieldName,
            null,
            "",
            "",
            'ng-model="'.$ngModelName.'.'.$ngFieldName.'"');
        self::rowEnd($model);
    }
    public static function activeTextBoxRow(CModel $model, $ngModelName, $ngFieldName) {
        self::rowStart($model, $ngFieldName);
        CHtml::textBox(
            $ngModelName."_".$ngFieldName,
            null,
            "",
            "",
            'ng-model="'.$ngModelName.'.'.$ngFieldName.'"');
        self::rowEnd($model);
    }
    public static function activeSelectRow(CModel $model, $ngModelName, $ngFieldName, $glossary) {
        self::rowStart($model, $ngFieldName);
        echo '<div ng-controller="LookupController as lookupCtrl" ng-init="lookupCtrl.initLookup(\''.$glossary.'\')">';

        echo '<ui-select style="width: 312px; " ng-model="'.$ngModelName.'.'.$ngFieldName.'" theme="select2">';
            echo '<ui-select-match placeholder="Выберите значение из списка">{{$select.selected.value}}</ui-select-match>';
            echo '<ui-select-choices repeat="item.key as item in items | filter: $select.search">';
                echo '<div ng-bind-html="item.value | highlight: $select.search"></div>';
            echo '</ui-select-choices>';
        echo '</ui-select>';


        echo '</div>';
        self::rowEnd($model);
        // будут по умолчанию на select2
        if (!self::$select2Init) {
            self::$select2Init = true;
            ?>
                <script src="<?php echo WEB_ROOT; ?>_core\_webapp\lookupController.js"></script>
                <style>
                    .select2 > .select2-choice.ui-select-match {
                        /* Because of the inclusion of Bootstrap */
                        height: 29px;
                    }
                </style>
            <?
        }
    }
}