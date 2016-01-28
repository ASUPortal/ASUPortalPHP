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

<h4>Вид итогового контроля</h4>

{CHtml::activeComponent("workplanfinalcontrol.php?plan_id={$plan->getId()}", $plan)}

<h4>4.1 Содержание разделов дисциплины</h4>

{include file="_corriculum/_workplan/contentCategories/subform.index.tpl"}

<h4>4.2. Структура дисциплины</h4>

{CHtml::activeComponent("workplancontent.php?plan_id={$plan->getId()}", $plan, ["defaultAction" => "structure"])}

<h4>4.3. Темы лекций</h4>

{CHtml::activeComponent("workplancontent.php?plan_id={$plan->getId()}", $plan, ["defaultAction" => "lectures"])}

<h4>4.4. Лабораторные работы</h4>

{CHtml::activeComponent("workplancontent.php?plan_id={$plan->getId()}", $plan, ["defaultAction" => "labworks"])}

<h4>4.5. Практические занятия (семинары)</h4>

{CHtml::activeComponent("workplancontent.php?plan_id={$plan->getId()}", $plan, ["defaultAction" => "practices"])}

<h4>4.6. Курсовой проект (работа)</h4>

<div class="control-group">
    {CHtml::activeLabel("project_description", $plan)}
    <div class="controls">
        {CHtml::activeTextBox("project_description", $plan, "project_description")}
        {CHtml::error("project_description", $plan)}
    </div>
</div>

<strong>Темы курсовых проектов (работ)</strong>

{CHtml::activeComponent("workplanprojectthemes.php?plan_id={$plan->getId()}", $plan)}

<h4>4.7. Расчётно-графическая работа</h4>

<div class="control-group">
    {CHtml::activeLabel("rgr_description", $plan)}
    <div class="controls">
        {CHtml::activeTextBox("rgr_description", $plan, "rgr_description")}
        {CHtml::error("rgr_description", $plan)}
    </div>
</div>

<strong>Темы расчётно-графических работ</strong>

{CHtml::activeComponent("workplanprojectthemes.php?type=1&plan_id={$plan->getId()}", $plan)}

<h4>4.8. Самостоятельное изучение разделов дисциплины</h4>

{CHtml::activeComponent("workplanselfeducationblocks.php?plan_id={$plan->getId()}", $plan)}

<script>
    jQuery(document).ready(function(){
        jQuery("#project_description").redactor({
            imageUpload: '{$web_root}_modules/_redactor/image_upload.php'
        });
        jQuery("#rgr_description").redactor({
            imageUpload: '{$web_root}_modules/_redactor/image_upload.php'
        });
    });
</script>