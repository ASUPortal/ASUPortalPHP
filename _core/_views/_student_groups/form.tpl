<form action="index.php" method="post">
{CHtml::hiddenField("action", "save")}
{CHtml::activeHiddenField("id", $group)}

    <p>{CHtml::errorSummary($group)}</p>

    <p>
        {CHtml::activeLabel("name", $group)}
        {CHtml::activeTextField("name", $group)}
        {CHtml::error("name", $group)}
    </p>

    <p>
        {CHtml::activeLabel("speciality_id", $group)}
        {CHtml::activeDropDownList("speciality_id", $group, CTaxonomyManager::getSpecialitiesList())}
        {CHtml::error("speciality_id", $group)}
    </p>

    {if !is_null($group->getId())}
    <p>
        {CHtml::activeLabel("head_student_id", $group)}
        {CHtml::activeDropDownList("head_student_id", $group, $students)}
        {CHtml::error("head_student_id", $group)}
    </p>
    {/if}

    <p>
        {CHtml::activeLabel("year_id", $group)}
        {CHtml::activeDropDownList("year_id", $group, CTaxonomyManager::getYearsList())}
        {CHtml::error("year_id", $group)}
    </p>

    <p>
        {CHtml::activeLabel("curator_id", $group)}
        {CHtml::activeDropDownList("curator_id", $group, CStaffManager::getPersonsList())}
        {CHtml::error("curator_id", $group)}
    </p>

    <p>
        {CHtml::activeLabel("corriculum_id", $group)}
        {CHtml::activeDropDownList("corriculum_id", $group, CCorriculumsManager::getCorriculumsList())}
        {CHtml::error("corriculum_id", $group)}
    </p>

    <p>
        {CHtml::activeLabel("comment", $group)}
        {CHtml::activeTextBox("comment", $group)}
        {CHtml::error("comment", $group)}
    </p>

    <p>
        {CHtml::submit("Сохранить")}
    </p>
</form>