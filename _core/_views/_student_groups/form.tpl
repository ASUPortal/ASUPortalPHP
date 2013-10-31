<form action="index.php" method="post" class="form-horizontal">
{CHtml::hiddenField("action", "save")}
{CHtml::activeHiddenField("id", $group)}

    {CHtml::errorSummary($group)}

    <div class="control-group">
        {CHtml::activeLabel("name", $group)}
        <div class="controls">
        {CHtml::activeTextField("name", $group)}
        {CHtml::error("name", $group)}
    </div>     </div>

    <div class="control-group">
        {CHtml::activeLabel("speciality_id", $group)}
        <div class="controls">
        {CHtml::activeDropDownList("speciality_id", $group, CTaxonomyManager::getSpecialitiesList())}
        {CHtml::error("speciality_id", $group)}
    </div>     </div>

    {if !is_null($group->getId())}
    <div class="control-group">
        {CHtml::activeLabel("head_student_id", $group)}
        <div class="controls">
        {CHtml::activeDropDownList("head_student_id", $group, $students)}
        {CHtml::error("head_student_id", $group)}
    </div>     </div>
    {/if}

    <div class="control-group">
        {CHtml::activeLabel("year_id", $group)}
        <div class="controls">
        {CHtml::activeDropDownList("year_id", $group, CTaxonomyManager::getYearsList())}
        {CHtml::error("year_id", $group)}
    </div>     </div>

    <div class="control-group">
        {CHtml::activeLabel("curator_id", $group)}
        <div class="controls">
        {CHtml::activeDropDownList("curator_id", $group, CStaffManager::getPersonsList())}
        {CHtml::error("curator_id", $group)}
    </div>     </div>

    <div class="control-group">
        {CHtml::activeLabel("corriculum_id", $group)}
        <div class="controls">
        {CHtml::activeDropDownList("corriculum_id", $group, CCorriculumsManager::getCorriculumsList())}
        {CHtml::error("corriculum_id", $group)}
    </div>     </div>

    <div class="control-group">
        {CHtml::activeLabel("comment", $group)}
        <div class="controls">
        {CHtml::activeTextBox("comment", $group)}
        {CHtml::error("comment", $group)}
    </div>     </div>

    <div class="control-group">
        <div class="controls">
        {CHtml::submit("Сохранить")}
    </div>     </div>
</form>