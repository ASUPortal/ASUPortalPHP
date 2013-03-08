{extends file="_core.3col.tpl"}

{block name="asu_center"}
<h2>Добавление показателя</h2>

    {CHtml::helpForCurrentPage()}

    <form action="indexes.php" method="post">
    <input type="hidden" name="action" value="save">
    {CHtml::activeHiddenField("id", $index)}

    <p>
        {CHtml::activeLabel("title", $index)}
        {CHtml::activeTextField("title", $index)}
        {CHtml::error("title", $index)}
    </p>
	
    <p>
        {CHtml::activeLabel("year_id", $index)}
        {CHtml::activeDropDownList("year_id", $index, CTaxonomyManager::getYearsList())}
        {CHtml::error("year_id", $index)}
    </p>	

	<div id="system_properties" style="display: none; ">
    <p>
        {CHtml::activeLabel("manager_class", $index)}
        {CHtml::activeTextField("manager_class", $index)}
        {CHtml::error("manager_class", $index)}
    </p>

    <p>
        {CHtml::activeLabel("manager_method", $index)}
        {CHtml::activeTextField("manager_method", $index)}
        {CHtml::error("manager_method", $index)}
    </p>

    <p>
        {CHtml::activeLabel("person_method", $index)}
        {CHtml::activeTextField("person_method", $index)}
        {CHtml::error("person_method", $index)}
    </p>

    <p>
        {CHtml::activeLabel("isMultivalue", $index)}
        {CHtml::activeTextField("isMultivalue", $index)}
        {CHtml::error("isMultivalue", $index)}
    </p>
	</div>
        
    <p>
        {CHtml::submit("Сохранить")}
    </p>
    </form>
{/block}

{block name="asu_right"}
{include file="_rating/index/add.right.tpl"}
{/block}