{NgHtml::activeTextBoxRow($plan, 'workplan', 'title')}

{NgHtml::activeSelectRow($plan, 'workplan', 'department_id', ["glossary"=>"departmentNames"])}

{NgHtml::activeTextBoxRow($plan, 'workplan', 'approver_post')}

{NgHtml::activeTextRow($plan, 'workplan', 'approver_name')}

{NgHtml::activeSelectRow($plan, 'workplan', 'direction_id', ["glossary"=>"corriculum_speciality_directions"])}

{NgHtml::activeSelectRow($plan, 'workplan', 'profiles', ["glossary"=>"corriculum_speciality_directions", "multiple"=>true, "onSelect" => "onProfileSelect"])}

{NgHtml::activeSelectRow($plan, 'workplan', 'qualification_id', ["glossary"=>"corriculum_skill"])}

{NgHtml::activeSelectRow($plan, 'workplan', 'education_form_id', ["glossary"=>"study_forms"])}

{NgHtml::activeTextRow($plan, 'workplan', 'year')}

{NgHtml::activeTextBoxRow($plan, 'workplan', 'intended_for')}

{NgHtml::activeSelectRow($plan, 'workplan', 'author_id', ["glossary"=>"staff"])}

<h3>1. Цели и задачи освоения дисциплины</h3>

<span class="btn btn-success btn-mini" ng-click="addTask()">Добавить задачу</span>

<table class="table table-striped">
    <tr ng-repeat="t in workplan.tasks">
        <td>
            <span class="btn btn-warning btn-mini" ng-click="removeTask($index)">Удалить задачу</span>
        </td>
        <td>
            {NgHtml::activeTextTagging("t", "task", ["glossary" => "workplan_tasks"])}
        <td>
    </tr>
</table>

<span class="btn btn-success btn-mini" ng-click="addGoal()">Добавить цель</span>

<table class="table table-striped">
    <tr ng-repeat="t in workplan.goals">
        <td>
            <span class="btn btn-warning btn-mini" ng-click="removeGoal($index)">Удалить цель</span>
        </td>
        <td>
            {NgHtml::activeTextTagging("t", "goal", ["glossary" => "workplan_goals"])}
        <td>
    </tr>
</table>

<h3>2. Место дисциплины в структуре ООП ВПО</h3>

{NgHtml::activeTextBoxRow($plan, 'workplan', 'position')}

{NgHtml::activeSelectRow($plan, 'workplan', 'disciplinesBefore', ["glossary"=>"subjects", "multiple"=>true])}

{NgHtml::activeSelectRow($plan, 'workplan', 'disciplinesAfter', ["glossary"=>"subjects", "multiple"=>true])}