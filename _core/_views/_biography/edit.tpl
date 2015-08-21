{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Редактирование биографии</h2>
	{CHtml::helpForCurrentPage()}
	
    {include file="_biography/form.tpl"}
{/block}

{block name="asu_right"}
    {include file="_biography/edit.right.tpl"}
{/block}