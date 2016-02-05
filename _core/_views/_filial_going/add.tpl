{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Добавление командировочного удостоверения</h2>
	{CHtml::helpForCurrentPage()}
	
    {include file="_filial_going/form.tpl"}
{/block}

{block name="asu_right"}
    {include file="_filial_going/add.right.tpl"}
{/block}