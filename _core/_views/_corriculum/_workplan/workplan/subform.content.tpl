<h3>4. Содержание и структура дисциплины (модуля)</h3>

<div class="control-group">
    {CHtml::activeLabel("module_id", $plan)}
    <div class="controls">
        {CHtml::activeLookup("module_id", $plan, "class.CSearchCatalogWorkPlanDisciplineModules", false, ["plan_id" => $plan->getId()])}
        {CHtml::error("module_id", $plan)}
    </div>
</div>

<h4>Семестры, в которых изучается дисциплина</h4>

{CHtml::activeComponent("workplanterms.php?plan_id={$plan->getId()}", $plan)}

<h4>4.1 Содержание разделов дисциплины</h4>

{include file="_corriculum/_workplan/contentModules/subform.index.tpl"}

<h4>4.2. Структура дисциплины</h4>

{CHtml::activeComponent("workplancontent.php?plan_id={$plan->getId()}", $plan, ["defaultAction" => "structure"])}

<h4>4.3. Лабораторные работы</h4>

{CHtml::activeComponent("workplancontent.php?plan_id={$plan->getId()}", $plan, ["defaultAction" => "labworks"])}

<h4>4.4. Практические занятия (семинары)</h4>

{CHtml::activeComponent("workplancontent.php?plan_id={$plan->getId()}", $plan, ["defaultAction" => "practices"])}

<h4>4.5. Курсовой проект</h4>

<div class="control-group">
    {CHtml::activeLabel("project_description", $plan)}
    <div class="controls">
        {CHtml::activeTextBox("project_description", $plan, "project_description")}
        {CHtml::error("project_description", $plan)}
    </div>
</div>

<strong>Темы курсовых проектов</strong>

{CHtml::activeComponent("workplanprojectthemes.php?plan_id={$plan->getId()}", $plan)}

<h4>4.6. Самостоятельное изучение разделов дисциплины</h4>

{include file="_corriculum/_workplan/selfEducationBlocks/subform.index.tpl"}

<script>
    jQuery(document).ready(function(){
        jQuery("#project_description").redactor({
            imageUpload: '{$web_root}_modules/_redactor/image_upload.php'
        });
    });
</script>