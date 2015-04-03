<h3>3. Требования к результатам освоения содержания дисциплины</h3>

<span class="btn btn-success btn-mini" ng-click="addCompetention()">Добавить компетенцию</span>

<table class="table table-striped">
    <tr ng-repeat="competention in workplan.competentions">
        <td>
            {NgHtml::activeSelectRow($plan, 'competention', 'competention_id', ["glossary"=>"corriculum_competentions"])}

            {NgHtml::activeSelectRow($plan, 'competention', 'knowledges', ["glossary"=>"corriculum_knowledges", "multiple"=>true, "onSelect" => "onCompetentionChildSelect"])}

            {NgHtml::activeSelectRow($plan, 'competention', 'skills', ["glossary"=>"corriculum_knowledges", "multiple"=>true, "onSelect" => "onCompetentionChildSelect"])}

            {NgHtml::activeSelectRow($plan, 'competention', 'experiences', ["glossary"=>"corriculum_knowledges", "multiple"=>true, "onSelect" => "onCompetentionChildSelect"])}

            <span class="btn btn-warning btn-mini" ng-click="removeCompetention($index)">Удалить компетенцию</span>
        </td>
    </tr>
</table>