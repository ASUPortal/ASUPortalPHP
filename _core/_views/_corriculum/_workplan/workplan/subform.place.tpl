<h3>1. Место дисциплины в структуре образовательной программы</h3>

<div class="control-group">
    {CHtml::activeLabel("position", $plan)}
    <div class="controls">
        {CHtml::activeTextBox("position", $plan)}
        {CHtml::error("position", $plan)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("disciplinesBefore", $plan)}
    <div class="controls">
        {CHtml::activeLookup("disciplinesBefore", $plan, "class.CSearchCatalogCorriculumDisciplines", true, ["plan_id" => $plan->getId()])}
        {CHtml::error("disciplinesBefore", $plan)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("disciplinesAfter", $plan)}
    <div class="controls">
        {CHtml::activeLookup("disciplinesAfter", $plan, "class.CSearchCatalogCorriculumDisciplines", true, ["plan_id" => $plan->getId()])}
        {CHtml::error("disciplinesAfter", $plan)}
    </div>
</div>

<h4>Цели и задачи освоения дисциплины</h4>
{include file="_corriculum/_workplan/goal/subform.index.tpl"}
{include file="_corriculum/_workplan/task/subform.index.tpl"}

<h4>Входные компетенции</h4>
{CHtml::activeComponent("workplancompetentions.php?type=1&plan_id={$plan->getId()}", $plan, ["withoutScripts" => "true"])}

<h4>Исходящие компетенции</h4>
{CHtml::activeComponent("workplancompetentions.php?type=2&plan_id={$plan->getId()}", $plan, ["withoutScripts" => "true"])}




