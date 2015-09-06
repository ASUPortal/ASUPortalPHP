<div class="control-group">
    {CHtml::activeLabel("title", $plan)}
    <div class="controls">
        {CHtml::activeTextBox("title", $plan)}
        {CHtml::error("title", $plan)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("department_id", $plan)}
    <div class="controls">
        {CHtml::activeLookup("department_id", $plan, "departmentNames")}
        {CHtml::error("department_id", $plan)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("approver_post", $plan)}
    <div class="controls">
        {CHtml::activeTextBox("approver_post", $plan)}
        {CHtml::error("approver_post", $plan)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("approver_name", $plan)}
    <div class="controls">
        {CHtml::activeTextBox("approver_name", $plan)}
        {CHtml::error("approver_name", $plan)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("direction_id", $plan)}
    <div class="controls">
        {CHtml::activeLookup("direction_id", $plan, "corriculum_speciality_directions")}
        {CHtml::error("direction_id", $plan)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("profiles", $plan)}
    <div class="controls">
        {CHtml::activeLookup("profiles", $plan, "corriculum_speciality_directions", true)}
        {CHtml::error("profiles", $plan)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("qualification_id", $plan)}
    <div class="controls">
        {CHtml::activeLookup("qualification_id", $plan, "corriculum_skill")}
        {CHtml::error("qualification_id", $plan)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("education_form_id", $plan)}
    <div class="controls">
        {CHtml::activeLookup("education_form_id", $plan, "study_forms")}
        {CHtml::error("education_form_id", $plan)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("year", $plan)}
    <div class="controls">
        {CHtml::activeTextField("year", $plan)}
        {CHtml::error("year", $plan)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("intended_for", $plan)}
    <div class="controls">
        {CHtml::activeTextBox("intended_for", $plan)}
        {CHtml::error("intended_for", $plan)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("authors", $plan)}
    <div class="controls">
        {CHtml::activeLookup("authors", $plan, "staff", true)}
        {CHtml::error("authors", $plan)}
    </div>
</div>

<h3>1. Цели и задачи освоения дисциплины</h3>

{CHtml::activeComponent("workplantasks.php?plan_id={$plan->getId()}", $plan)}

{CHtml::activeComponent("workplangoals.php?plan_id={$plan->getId()}", $plan)}

<h3>2. Место дисциплины в структуре ООП ВПО</h3>

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
        {CHtml::activeLookup("disciplinesBefore", $plan, "subjects", true)}
        {CHtml::error("disciplinesBefore", $plan)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("disciplinesAfter", $plan)}
    <div class="controls">
        {CHtml::activeLookup("disciplinesAfter", $plan, "subjects", true)}
        {CHtml::error("disciplinesAfter", $plan)}
    </div>
</div>