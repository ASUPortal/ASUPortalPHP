<h3>3. Требования к результатам освоения содержания дисциплины</h3>

<span class="btn btn-success btn-mini" ng-click="addCompetention()">Добавить компетенцию</span>

<table class="table table-striped">
    <tr ng-repeat="competention in workplan.competentions">
        <td>
            {NgHtml::activeSelectRow($plan, 'competention', 'competention_id', 'corriculum_competentions')}

            {NgHtml::activeSelectRow($plan, 'competention', 'knowledges', 'corriculum_knowledges', true)}

            {NgHtml::activeSelectRow($plan, 'competention', 'skills', 'corriculum_knowledges', true)}

            {NgHtml::activeSelectRow($plan, 'competention', 'experiences', 'corriculum_knowledges', true)}

            <span class="btn btn-warning btn-mini" ng-click="removeCompetention($index)">Удалить компетенцию</span>
        </td>
    </tr>
</table>