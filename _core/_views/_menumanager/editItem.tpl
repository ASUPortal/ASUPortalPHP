{extends file="_core.3col.tpl"}

{block name="asu_center"}
<h2>Редактирование пункта меню {$item->getName()}</h2>
{CHtml::helpForCurrentPage()}

{include file="_menumanager/formItem.tpl"}
{/block}

{block name="asu_right"}

{/block}