{extends file="_core.3col.tpl"}

{block name="asu_center"}
<h2>Добавление новой коллекции поиска Solr</h2>

    {CHtml::helpForCurrentPage()}

    {include file="_search/_settings/form.tpl"}
{/block}

{block name="asu_right"}
	{include file="_search/_settings/common.right.tpl"}
{/block}