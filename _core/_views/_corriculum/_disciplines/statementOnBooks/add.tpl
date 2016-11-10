{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Формирование заявки</h2>

    {CHtml::helpForCurrentPage()}
    
    {include file="_corriculum/_disciplines/statementOnBooks/form.tpl"}
	    
{/block}

{block name="asu_right"}
	{include file="_corriculum/_disciplines/common.right.tpl"}
{/block}