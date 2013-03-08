{extends file="_core.3col.tpl"}

{block name="asu_center"}
<h2>Генерация билетов</h2>

<form action="index.php" method="post">
    <input type="hidden" name="action" value="generateTickets">
    <p>
        {CHtml::activeLabel("speciality_id", $generate)}
        {CHtml::activeDropDownList("speciality_id", $generate, CExamManager::getSpecialitiesWithQuestionsList(), "speciality_id")}
        {CHtml::error("speciality_id", $generate)}
    </p>

    <p id="course_group" style="display: none; ">
        {CHtml::activeLabel("course", $generate)}
        {CHtml::activeDropDownList("course", $generate, $cources, "course_id")}
        {CHtml::error("course", $generate)}
    </p>

    <p id="year_group" style="display: none; ">
        {CHtml::activeLabel("year_id", $generate)}
        {CHtml::activeDropDownList("year_id", $generate, CTaxonomyManager::getYearsList(), "year_id")}
        {CHtml::error("year_id", $generate)}
    </p>
    
    <div id="disciplines_placeholder" style="display: none; border: 1px dotted #c0c0c0;">
        <div id="disciplines_adder" style="cursor: pointer; text-align: center; background: #00FFFF; padding: 5px; font-weight: bold;">Добавить дисциплину</div>

        <p id="discipline_group" style="display: none; ">
            {CHtml::activeLabel("discipline_id", $generate)}
            {CHtml::activeDropDownList("discipline_id", $generate, array(), "discipline_id")}
            {CHtml::error("discipline_id", $generate)}

            <span style="cursor: pointer; text-align: right; text-decoration: underline;" class="disciplineRemover">Удалить</span>
        </p>

        <p id="category_group" style="display: none;">
            {CHtml::activeLabel("category_id", $generate)}
            {CHtml::activeDropDownList("category_id", $generate, array(), "category_id")}
            {CHtml::error("category_id", $generate)}
        </p>
    </div>

    <p>
        {CHtml::activeLabel("approver_id", $generate)}
        {CHtml::activeDropDownList("approver_id", $generate, CStaffManager::getPersonsList(), "approver_id")}
        {CHtml::error("approver_id", $generate)}
        {CHtml::personTypeFilter("approver_id", $generate)}
    </p>

    <p>
        {CHtml::activeLabel("protocol_id", $generate)}
        {CHtml::activeDropDownList("protocol_id", $generate, CProtocolManager::getAllDepProtocolsList(), "approver_id")}
        {CHtml::error("protocol_id", $generate)}
    </p>

    <p>
        {CHtml::activeLabel("number", $generate)}
        {CHtml::activeTextField("number", $generate)}
        {CHtml::error("number", $generate)}
    </p>

    <p>
        {CHtml::submit("Сохранить")}
    </p>
</form>
{/block}

{block name="asu_right"}
{include file="_examination/edit.right.tpl"}
{/block}