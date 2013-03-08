{extends file="_core.3col.tpl"}

{block name="asu_center"}
<h2>Групповое добавление вопросов</h2>

<form action="index.php" method="POST">
    <input type="hidden" name="action" value="saveGroup">
    <p>
        {CHtml::activeLabel("speciality_id", $group)}
        {CHtml::activeDropDownList("speciality_id", $group, CTaxonomyManager::getSpecialitiesList())}
        {CHtml::error("speciality_id", $group)}
    </p>

    <p>
        {CHtml::activeLabel("course", $group)}
        {CHtml::activeDropDownList("course", $group, $cources)}
        {CHtml::error("course", $group)}
    </p>

    <p>
        {CHtml::activeLabel("year_id", $group)}
        {CHtml::activeDropDownList("year_id", $group, CTaxonomyManager::getYearsList())}
        {CHtml::error("year_id", $group)}
    </p>

    <p>
        {CHtml::activeLabel("category_id", $group)}
        {CHtml::activeDropDownList("category_id", $group, CTaxonomyManager::getTaxonomy("questions_types")->getTermsList())}
        {CHtml::error("category_id", $group)}
    </p>

    <p>
        {CHtml::activeLabel("discipline_id", $group)}
        {CHtml::activeDropDownList("discipline_id", $group, CTaxonomyManager::getDisciplinesList())}
        {CHtml::error("discipline_id", $group)}
    </p>

    <p>
        {CHtml::activeLabel("text", $group)}
        {CHtml::activeTextBox("text", $group)}
        {CHtml::error("text", $group)}
    </p>

    <p>
        {CHtml::submit("Сохранить")}
    </p>
</form>
{/block}

{block name="asu_right"}
{include file="_examination/edit.right.tpl"}
{/block}