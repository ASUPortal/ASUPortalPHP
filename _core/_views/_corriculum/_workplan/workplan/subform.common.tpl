<div class="control-group">
    {CHtml::activeLabel("title_display", $plan)}
    <div class="controls">
        {CHtml::activeTextBox("title_display", $plan)}
        {CHtml::error("title_display", $plan)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("title", $plan)}
    <div class="controls">
        {CHtml::activeTextBox("title", $plan)}
        {CHtml::error("title", $plan)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("is_archive", $plan)}
    <div class="controls">
        {CHtml::activeCheckBox("is_archive", $plan)}
        {CHtml::error("is_archive", $plan)}
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
        {CHtml::activeLookup("approver_post", $plan, "approver_workplan_posts", false, array(), true)}
        {CHtml::error("approver_post", $plan)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("approver_name", $plan)}
    <div class="controls">
        {CHtml::activeLookup("approver_name", $plan, "approver_workplan_names", false, array(), true)}
        {CHtml::error("approver_name", $plan)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("level_id", $plan)}
    <div class="controls">
        {CHtml::activeLookup("level_id", $plan, "corriculum_level_of_training", false, array(), true)}
        {CHtml::error("level_id", $plan)}
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
        {CHtml::activeLookup("profiles", $plan, "corriculum_profiles", true, array(), true)}
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
	{CHtml::activeLabel("date_of_formation", $plan)}
	<div class="controls">
		{CHtml::activeDateField("date_of_formation", $plan)}
		{CHtml::error("date_of_formation", $plan)}
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

<div class="control-group">
    {CHtml::activeLabel("comment", $plan)}
    <div class="controls">
        {CHtml::activeTextBox("comment", $plan)}
        {CHtml::error("comment", $plan)}
    </div>
</div>