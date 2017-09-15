{extends file="_core.3col.tpl"}

{block name="asu_center"}
	<h2>Управление версиями модели {$modelName}</h2>
    {CHtml::helpForCurrentPage()}
    
	{if $items->getCount() == 0}
		Нет объектов для отображения
	{else}
		<form action="index.php" method="post" id="MainView">
		{CHtml::hiddenField("itemId", $itemId)}
		{CHtml::hiddenField("class", $class)}
		{CHtml::hiddenField("module", $module)}
		
	    <table class="table table-striped table-bordered table-hover table-condensed">
	        <tr>
	            <th></th>
	            <th>#</th>
	            <th>{CHtml::activeViewGroupSelect("id", $items->getFirstItem(), true)}</th>
	            <th>Идентификатор записи</th>
	            <th>Идентификатор версии</th>
	            <th>Дата и время создания записи</th>
	            <th>Сотрудник, кем запись была создана</th>
	            <th>Последняя версия</th>
	        </tr>
	        {counter start=0 print=false}
	        {foreach $items->getItems() as $item}
	        <tr>
	            <td>
	            	{if (!$item->_is_last_version)}
	            		<a href="#" class="icon-trash" onclick="if (confirm('Действительно удалить запись {$item->id}')) { location.href='?action=delete&id={$item->id}&class={$class}&itemId={$itemId}&module={$module}'; }; return false;"></a></td>
	            	{/if}
	            </td>
	            <td>{counter}</td>
	            <td>
	            	{if (!$item->_is_last_version)}
	            		{CHtml::activeViewGroupSelect("id", $item, false)}
	            	{/if}
	            </td>
	            <td><a href="../../_modules/{$module}/index.php?action=edit&id={$item->getId()}" target="_blank">{$item->getId()}</a></td>                       
	            <td>{$item->_version_of}</td>
	            <td>{$item->_created_at}</td>
	            <td>{CStaffManager::getPersonById($item->_created_by)->fio}</td>
	            <td>
	            	{if ($item->_is_last_version)}
	            		Да
	            	{else}
	            		Нет
	            	{/if}
	            </td>
	        </tr>
	        {/foreach}
	    </table>
	    </form>
    {/if}
{/block}

{block name="asu_right"}
	{include file="_version_controls/common.right.tpl"}
{/block}