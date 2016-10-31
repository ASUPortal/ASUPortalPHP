<h3>3. Содержание и структура дисциплины (модуля)</h3>

<div class="control-group">
    {CHtml::activeLabel("module_id", $plan)}
    <div class="controls">
        {CHtml::activeLookup("module_id", $plan, "class.CSearchCatalogWorkPlanDisciplineModules", false, ["plan_id" => $plan->getId()])}
        {CHtml::error("module_id", $plan)}
    </div>
</div>

<h4>Семестры, в которых изучается дисциплина</h4>

{CHtml::activeComponent("workplanterms.php?plan_id={$plan->getId()}", $plan)}

<h4>Вид итогового контроля</h4>

{CHtml::activeComponent("workplanfinalcontrol.php?plan_id={$plan->getId()}", $plan)}

<h4>Вид промежуточного контроля</h4>

{CHtml::activeComponent("workplanmediumcontrol.php?plan_id={$plan->getId()}", $plan)}

<h3>3.1. Содержание разделов дисциплины</h3>

{include file="_corriculum/_workplan/contentCategories/subform.index.tpl"}

<h4>3.2. Структура дисциплины</h4>

{CHtml::activeComponent("workplancontent.php?plan_id={$plan->getId()}", $plan, ["defaultAction" => "structure"])}

<h4>3.3. Темы лекций</h4>

{CHtml::activeComponent("workplancontent.php?plan_id={$plan->getId()}", $plan, ["defaultAction" => "lectures"])}

<h4>3.4. Лабораторные работы</h4>

{CHtml::activeComponent("workplancontent.php?plan_id={$plan->getId()}", $plan, ["defaultAction" => "labworks"])}

<h4>3.5. Практические занятия (семинары)</h4>

{CHtml::activeComponent("workplancontent.php?plan_id={$plan->getId()}", $plan, ["defaultAction" => "practices"])}