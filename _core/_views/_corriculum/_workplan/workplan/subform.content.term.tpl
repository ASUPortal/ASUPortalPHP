<h5>Разделы дисциплины, изучаемые в <span ng-bind="term.number" /> семестре</h5>


<span class="btn btn-success btn-mini" ng-click="addTermSection($index)">Добавить раздел</span>
<table class="table table-striped">
    <tr>
        <td rowspan="2">
            № раздела
        </td>
        <td rowspan="2">
           Наименование раздела
        </td>
        {literal}
        <td colspan="{{term.types.length }}">
            Количество часов
        </td>
        {/literal}
    </tr>
    <tr>
        <td ng-repeat="type in term.types">
            <span ng-bind="type.type.name" />
        </td>
    </tr>

    <tr ng-repeat="section in term.sections">
        <td>
            <span ng-bind="$index" />
        </td>
        <td>
            {NgHtml::activeTextBox("section", "title", ["html" => 'style="width: 100%; "'])}
        </td>
    </tr>
</table>