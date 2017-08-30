{extends file="_core.component.tpl"}

{block name="asu_center"}
<h2>Добавление новой настройки поиска Solr</h2>

    {CHtml::helpForCurrentPage()}

    {include file="_search/_settingsList/form.tpl"}
{/block}

{block name="asu_right"}
	{include file="_search/_settingsList/common.right.tpl"}
{/block}