{extends file="_core.3col.tpl"}

{block name="asu_center"}
<h2>Групповое добавление вопросов</h2>

<form action="index.php" method="POST" class="form-horizontal">
    <input type="hidden" name="action" value="saveGroup">
    <div class="control-group">
        {CHtml::activeLabel("speciality_id", $group)}
        <div class="controls">
            {CHtml::activeDropDownList("speciality_id", $group, CTaxonomyManager::getSpecialitiesList())}
            {CHtml::error("speciality_id", $group)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("course", $group)}
        <div class="controls">
            {CHtml::activeDropDownList("course", $group, $cources)}
            {CHtml::error("course", $group)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("year_id", $group)}
        <div class="controls">
            {CHtml::activeDropDownList("year_id", $group, CTaxonomyManager::getYearsList())}
            {CHtml::error("year_id", $group)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("category_id", $group)}
        <div class="controls">
            {CHtml::activeDropDownList("category_id", $group, CTaxonomyManager::getTaxonomy("questions_types")->getTermsList())}
            {CHtml::error("category_id", $group)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("discipline_id", $group)}
        <div class="controls">
            {CHtml::activeDropDownList("discipline_id", $group, CTaxonomyManager::getDisciplinesList())}
            {CHtml::error("discipline_id", $group)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("text", $group)}
        <div class="controls">
            {CHtml::activeTextBox("text", $group)}
            {CHtml::error("text", $group)}
        </div>
    </div>

    <div class="control-group">
        <div class="controls">
            {CHtml::submit("Сохранить")}
        </div>
    </div>
</form>
{/block}

{block name="asu_right"}
{include file="_examination/edit.right.tpl"}
{/block}