{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Добавление дисциплины</h2>
	{CHtml::helpForCurrentPage()}
	
    {include file="_discipline/form.tpl"}
{/block}

{block name="asu_right"}
    {include file="_discipline/add.right.tpl"}
{/block}