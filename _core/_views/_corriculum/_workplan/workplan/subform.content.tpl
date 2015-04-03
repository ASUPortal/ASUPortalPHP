<h3>4. Содержание и структура дисциплины (модуля)</h3>
<h4>4.1 Содержание разделов дисциплины</h4>

<span class="btn btn-success btn-mini" ng-click="addSection()">Добавить раздел</span>

<table class="table table-striped">
    <tr ng-repeat="section in workplan.sections" ng-init="sectionIndex = $index">
        <td>
            {NgHtml::activeTextBoxRow($plan, "section", "name")}

            <div class="control-group">
                {CHtml::activeLabel("lectures", $plan)}
                <div class="controls">
                    <table width="100%">
                        <tr ng-repeat="lecture in section.lectures">
                            <td>
                                {NgHtml::activeTextBox("lecture", "lecture_title", ["html" => ''])}
                            </td>
                            <td width="200px">
                                <span class="btn btn-warning btn-mini" ng-click="removeLecture(sectionIndex, $index)">Удалить содержимое</span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <span class="btn btn-success btn-mini" ng-click="addLecture($index)">Добавить содержимое</span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            {NgHtml::activeSelectRow($plan, "section", "controls", ["multiple" => true, "glossary" => "corriculum_labor_form"])}

            <span class="btn btn-warning btn-mini" ng-click="removeSection($index)">Удалить раздел</span>
        </td>
    </tr>
</table>

<h4>4.2. Структура дисциплины</h4>

<div ng-controller="WorkPlanTermsController as wptCtrl">
    <span class="btn btn-success btn-mini" ng-click="addTerm()">Добавить семестр</span>
    <table class="table table-striped">
        <tr>
            <td rowspan="2">
                Вид работы
            </td>
            {literal}
            <td colspan="{{workplan.terms.length}}">
                Трудоемкость, часов
            </td>
            {/literal}
        </tr>
        <tr>
            <td ng-repeat="term in workplan.terms">
                семестр
            </td>
        </tr>
    </table>
</div>