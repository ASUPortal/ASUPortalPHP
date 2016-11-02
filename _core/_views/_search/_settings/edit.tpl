{extends file="_core.3col.tpl"}

{block name="asu_center"}
<h2>Редактирование коллекции поиска Solr</h2>

    {CHtml::helpForCurrentPage()}

	{include file="_search/_settings/form.tpl"}
	
	<h3>Настройки коллекции поиска Solr</h3>
	{CHtml::activeComponent("settingsList.php?core_id={$setting->getId()}", $setting)}
	
{/block}

{block name="asu_right"}
	{include file="_search/_settings/common.right.tpl"}
{/block}