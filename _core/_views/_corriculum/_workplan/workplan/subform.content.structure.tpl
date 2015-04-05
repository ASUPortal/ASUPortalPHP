
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
                {NgHtml::activeText("term", "number", ["html" => 'style="width: 100%; "'])}
            </td>
        </tr>
        <tr ng-repeat="type in workplan.terms[0].types" ng-init="type_id = $index">
            <td>
                {NgHtml::activeSelect("type", "type_id", ["glossary" => "corriculum_labor_types", "onSelect" => "onTypeSelect"])}
            </td>
            <td ng-repeat="term in workplan.terms">
                <input type="text" ng-model="term.types[type_id].value">
            </td>
        </tr>
        <tr>
            {literal}
                <td>
                    <span class="btn btn-warning btn-mini" ng-click="addTermLoad($index)">Добавить вид работы</span>
                </td>
            {/literal}
            <td ng-repeat="term in workplan.terms">
                <span class="btn btn-warning btn-mini" ng-click="removeTerm($index)">Удалить семестр</span>
            </td>
        </tr>
    </table>

    <div ng-repeat="term in workplan.terms">
        {include file="_corriculum/_workplan/workplan/subform.content.term.tpl"}
    </div>