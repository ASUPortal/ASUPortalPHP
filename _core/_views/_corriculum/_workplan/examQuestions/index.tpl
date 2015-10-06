{extends file="_core.component.tpl"}

{block name="asu_center"}
    {if ($objects->getCount() == 0)}
        Нет объектов для отображения
    {else}
    	<table class="table table-striped table-bordered table-hover table-condensed">
	        <tr>
	            <th>#</th>
	            <th>&nbsp;</th>
	            <th>Направление подготовки</th>
	            <th>Курс</th>
	            <th>Учебный год</th>
	            <th>Дисциплина</th>
	            <th>Категория</th>
	            <th>Вопрос</th>
	        </tr>
	        {counter start=($paginator->getRecordSet()->getPageSize() * ($paginator->getCurrentPageNumber() - 1)) print=false}
	        {foreach $objects->getItems() as $object}
		        <tr>
		            <td>{counter}</td>
		            <td><a href="#" class="icon-trash" onclick="if (confirm('Действительно удалить вопрос {$object->text}')) { location.href='workplanexamquestions.php?action=delete&id={$object->id}'; }; return false;"></a></td>
		            <td>
		            	{if !is_null($object->speciality)}
		            		{$object->speciality->getValue()}
		            	{/if}
		            </td>
		            <td>{$object->course}</td>
		            <td>
		            	{if !is_null($object->year)}
		            		{$object->year->getValue()}
		            	{/if}
		            </td>
		            <td>
		            	{if !is_null($object->discipline)}
		            		{$object->discipline->getValue()}
		            	{/if}
		            </td>
		            <td>
		            	{if !is_null($object->category)}
		            		{$object->category->getValue()}
		            	{/if}
		            </td>
		            <td><a href="workplanexamquestions.php?action=edit&id={$object->getId()}">{$object->text|nl2br}</a></td>
		        </tr>
	        {/foreach}
    	</table>
        {CHtml::paginator($paginator, "workplanexamquestions.php?action=index")}
    {/if}
{/block}

{block name="asu_right"}
    {include file="_corriculum/_workplan/examQuestions/common.right.tpl"}
{/block}