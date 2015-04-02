<h3>4. Содержание и структура дисциплины (модуля)</h3>
<h4>4.1 Содержание разделов дисциплины</h4>

<span class="btn btn-success btn-mini" ng-click="addSection()">Добавить раздел</span>

<table class="table table-striped">
    <tr>
        <th>№ разд.</th>
        <th>Наименование раздела</th>
        <th>Содержание раздела</th>
        <th>Форма текущего контроля</th>
    </tr>
    <tr ng-repeat="section in workplan.sections">
        <td><span ng-bind="section.sectionIndex"></span></td>
        <td>
            {NgHtml::activeTextBox("section", "name", ["html" => 'style="width: 100%; "'])}
        </td>
        <td>
            <span class="btn btn-success btn-mini" ng-click="addLecture($index)">Добавить содержимое</span>
            <table>
                <tr ng-repeat="lecture in section.lectures">
                    <td>
                        {NgHtml::activeTextBox("lecture", "lecture_title", ["html" => 'style="width: 100%; "'])}
                    </td>
                </tr>
            </table>
        </td>
        <td>
            {NgHtml::activeSelect("section", "controls", ["multiple" => true, "glossary" => "corriculum_labor_form"])}
        </td>
    </tr>
</table>