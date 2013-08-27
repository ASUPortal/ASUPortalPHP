{extends file="_core.3col.tpl"}

{block name="asu_center"}
<h2>Добавление показателя</h2>

    {CHtml::helpForCurrentPage()}

    <form action="indexes.php" method="post" class="form-horizontal">
    <input type="hidden" name="action" value="save">
    {CHtml::activeHiddenField("id", $index)}

    <div class="control-group">
        {CHtml::activeLabel("title", $index)}
        <div class="controls">
        {CHtml::activeTextField("title", $index)}
        {CHtml::error("title", $index)}
    </div></div>
	
    <div class="control-group">
        {CHtml::activeLabel("year_id", $index)}
        <div class="controls">
        {CHtml::activeDropDownList("year_id", $index, CTaxonomyManager::getYearsList())}
        {CHtml::error("year_id", $index)}
    </div></div>	

	<div id="system_properties" style="display: none; ">
    <div class="control-group">
        {CHtml::activeLabel("manager_class", $index)}
        <div class="controls">
        {CHtml::activeTextField("manager_class", $index)}
        {CHtml::error("manager_class", $index)}
    </div></div>

    <div class="control-group">
        {CHtml::activeLabel("manager_method", $index)}
        <div class="controls">
        {CHtml::activeTextField("manager_method", $index)}
        {CHtml::error("manager_method", $index)}
    </div></div>

    <div class="control-group">
        {CHtml::activeLabel("person_method", $index)}
        <div class="controls">
        {CHtml::activeTextField("person_method", $index)}
        {CHtml::error("person_method", $index)}
    </div></div>

    <div class="control-group">
        {CHtml::activeLabel("isMultivalue", $index)}
        <div class="controls">
        {CHtml::activeTextField("isMultivalue", $index)}
        {CHtml::error("isMultivalue", $index)}
    </div></div>
	</div>

        <div class="control-group">
            <div class="controls">
                {CHtml::submit("Сохранить")}
            </div></div>
    </form>
{/block}

{block name="asu_right"}
{include file="_rating/index/add.right.tpl"}
{/block}