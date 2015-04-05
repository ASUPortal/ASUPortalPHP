<table class="table table-striped">
    <tr>
        <th>№ ЛР</th>
        <th>№ раздела</th>
        <th>Наименование лабораторной работы</th>
        <th>Кол-во часов</th>
    </tr>
    <tbody ng-repeat="term in workplan.terms">
        <tr>
            {literal}
            <td colspan="4">
                Семестр {{term.number}}
                {/literal}
                <span class="btn btn-success btn-mini" ng-click="addLab($index)">Добавить лабораторную работу</span>
            </td>
        </tr>

        <tr ng-repeat="lab in term.labs">
            <td>{NgHtml::activeText("lab", "lab_num", ["html" => 'style="width: 100%; "'])}</td>
            <td>{NgHtml::activeText("lab", "section_num", ["html" => 'style="width: 100%; "'])}</td>
            <td>{NgHtml::activeTextBox("lab", "title", ["html" => 'style="width: 100%; "'])}</td>
            <td>{NgHtml::activeText("lab", "hours", ["html" => 'style="width: 100%; "'])}</td>
        </tr>
    </tbody>
</table>