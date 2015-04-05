<table class="table table-striped">
    <tr>
        <th>№ ЛР</th>
        <th>№ раздела</th>
        <th>Тема</th>
        <th>Кол-во часов</th>
    </tr>
    <tbody ng-repeat="term in workplan.terms">
    <tr>
        {literal}
        <td colspan="4">
            Семестр {{term.number}}
            {/literal}
            <span class="btn btn-success btn-mini" ng-click="addPractice($index)">Добавить практику</span>
        </td>
    </tr>

    <tr ng-repeat="lab in term.practices">
        <td>{NgHtml::activeText("lab", "practice_num", ["html" => 'style="width: 100%; "'])}</td>
        <td>{NgHtml::activeText("lab", "section_num", ["html" => 'style="width: 100%; "'])}</td>
        <td>{NgHtml::activeTextBox("lab", "title", ["html" => 'style="width: 100%; "'])}</td>
        <td>{NgHtml::activeText("lab", "hours", ["html" => 'style="width: 100%; "'])}</td>
    </tr>
    </tbody>
</table>