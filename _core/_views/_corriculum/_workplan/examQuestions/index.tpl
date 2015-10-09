{extends file="_core.component.tpl"}

{block name="asu_center"}
    {if ($objects->getCount() == 0)}
        Нет объектов для отображения
    {else}
    	<table class="table table-striped table-bordered table-hover table-condensed">
	        <tr>
	            <th>&nbsp;</th>
	            <th>#</th>
	            <th>Вопрос</th>
	        </tr>
	        {counter start=($paginator->getRecordSet()->getPageSize() * ($paginator->getCurrentPageNumber() - 1)) print=false}
	        {foreach $objects->getItems() as $object}
		        <tr>
		            <td><a href="#" class="icon-trash" onclick="if (confirm('Действительно удалить вопрос {$object->text}')) { location.href='workplanexamquestions.php?action=delete&id={$object->id}'; }; return false;"></a></td>
		            <td>{counter}</td>
		            <td><a href="workplanexamquestions.php?action=edit&id={$object->getId()}">{$object->text|nl2br}</a></td>
		        </tr>
	        {/foreach}
    	</table>
    {/if}
{/block}

{block name="asu_right"}
    {include file="_corriculum/_workplan/examQuestions/common.right.tpl"}
{/block}