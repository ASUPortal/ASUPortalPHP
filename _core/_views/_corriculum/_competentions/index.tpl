{extends file="_core.component.tpl"}

{block name="asu_center"}
    <h2>Управление компетенциями</h2>
    {CHtml::helpForCurrentPage()}

    {if $objects->getCount() == 0}
		Нет компетенций для отображения
	{else}
	<form action="competentions.php" method="post" id="MainView">
	<table class="table table-striped table-bordered table-hover table-condensed">
	    <tr>
	        <th>&nbsp;</th>
	        <th>{CHtml::activeViewGroupSelect("id", $objects->getFirstItem(), true)}</th>
	        <th>#</th>
	        <th>Компетенция</th>
	        <th>Уровень освоения</th>
	        <th>Знать</th>
	        <th>Уметь</th>
	        <th>Владеть</th>
	    </tr>
	    {counter start=0 print=false}
	    {foreach $objects->getItems() as $comp}
	        <tr>
	            <td><a href="#" class="icon-trash" onclick="if (confirm('Действительно удалить компетенцию')) { location.href='competentions.php?action=delete&id={$comp->getId()}'; }; return false;"></a></td>
	            <td>{CHtml::activeViewGroupSelect("id", $comp)}</td>
	            <td>{counter}</td>
	            <td>
	                {if !is_null($comp->competention)}
	                    <a href="competentions.php?action=edit&id={$comp->getId()}">{$comp->competention->getValue()}</a>
	                {/if}
	            </td>
	            <td>
	                {if !is_null($comp->level)}
	                    {$comp->level->getValue()}
	                {/if}
	            </td>
	            <td>
	            	{foreach $comp->knowledges->getItems() as $o}
	            		<p>{$o}</p>
	            	{/foreach}
	            </td>
	            <td>
	            	{foreach $comp->skills->getItems() as $o}
	            		<p>{$o}</p>
	            	{/foreach}
	            </td>
	            <td>
	            	{foreach $comp->experiences->getItems() as $o}
	            		<p>{$o}</p>
	            	{/foreach}
	            </td>
	        </tr>
	    {/foreach}
	</table>
	{/if}
	</form>
{/block}

{block name="asu_right"}
    {include file="_corriculum/_competentions/common.right.tpl"}
{/block}