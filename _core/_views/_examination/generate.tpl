{extends file="_core.3col.tpl"}

{block name="asu_center"}
<h2>Генерация билетов</h2>
{CHtml::helpForCurrentPage()}

<form action="index.php" method="post" class="form-horizontal">
    <input type="hidden" name="action" value="generateTickets">
    <div class="control-group">
        {CHtml::activeLabel("speciality_id", $generate)}
        <div class="controls">
            {CHtml::activeDropDownList("speciality_id", $generate, CExamManager::getSpecialitiesWithQuestionsList(), "speciality_id")}
            {CHtml::error("speciality_id", $generate)}
        </div>
    </div>

    <div class="control-group" id="course_group" style="display: none; ">
        {CHtml::activeLabel("course", $generate)}
        <div class="controls">
            {CHtml::activeDropDownList("course", $generate, $cources, "course_id")}
            {CHtml::error("course", $generate)}
        </div>
    </div>

    <div class="control-group" id="year_group" style="display: none; ">
        {CHtml::activeLabel("year_id", $generate)}
        <div class="controls">
            {CHtml::activeDropDownList("year_id", $generate, CTaxonomyManager::getYearsList(), "year_id")}
            {CHtml::error("year_id", $generate)}
        </div>
    </div>
    
    <div id="disciplines_placeholder" style="display: none; border: 1px dotted #c0c0c0;">
        <div id="disciplines_adder" style="cursor: pointer; text-align: center; background: #00FFFF; padding: 5px; font-weight: bold;">Добавить дисциплину</div>

        <div class="control-group" id="discipline_group" style="display: none; ">
            {CHtml::activeLabel("discipline_id", $generate)}
            <div class="controls">
                {CHtml::activeDropDownList("discipline_id", $generate, array(), "discipline_id")}
                {CHtml::error("discipline_id", $generate)}
            <span style="cursor: pointer; text-align: right; text-decoration: underline;" class="disciplineRemover">Удалить</span>
            </div>
        </div>

        <div class="control-group" id="category_group" style="display: none;">
            {CHtml::activeLabel("category_id", $generate)}
            <div class="controls">
                {CHtml::activeDropDownList("category_id", $generate, array(), "category_id")}
                {CHtml::error("category_id", $generate)}
            </div>
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("approver_id", $generate)}
        <div class="controls">
            {CHtml::activeDropDownList("approver_id", $generate, CStaffManager::getPersonsList(), "approver_id")}
            {CHtml::error("approver_id", $generate)}
            {CHtml::personTypeFilter("approver_id", $generate)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("protocol_id", $generate)}
        <div class="controls">
            {CHtml::activeDropDownList("protocol_id", $generate, CProtocolManager::getAllDepProtocolsList(), "approver_id")}
            {CHtml::error("protocol_id", $generate)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("number", $generate)}
        <div class="controls">
            {CHtml::activeTextField("number", $generate)}
            {CHtml::error("number", $generate)}
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