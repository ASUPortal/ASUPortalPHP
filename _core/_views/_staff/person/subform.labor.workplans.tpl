<table class="table table-striped table-bordered table-hover table-condensed">
    <tr>
        <th>#</th>
        <th>&nbsp;</th>
        <th>Отображаемое наименование</th>
        <th>Дисциплина</th>
        <th>Учебный план</th>
        <th>Год</th>
        <th>Профили</th>
        <th>Авторы</th>
        <th>Наименование</th>
    </tr>
    {counter start=0 print=false}
    {foreach $form->person->workplans->getItems() as $workplan}
        <tr>
            <td>{counter}</td>
            <td><a href="{$web_root}_modules/_corriculum/workplans.php?action=edit&id={$workplan->getId()}" class="icon-pencil"></a></td>
            <td>{$workplan->title_display}</td>
            <td>{$workplan->discipline}</td>
            <td>
            	{if !is_null($workplan->corriculumDiscipline)}
	            	{if !is_null($workplan->corriculumDiscipline->cycle)}
		            	{if !is_null($workplan->corriculumDiscipline->cycle->corriculum)}
		            		<a href="{$web_root}_modules/_corriculum/?action=view&id={$workplan->corriculumDiscipline->cycle->corriculum->getId()}">{$workplan->corriculumDiscipline->cycle->corriculum->title}</a>
		            	{/if}
	            	{/if}
            	{/if}
            </td>
            <td>{$workplan->year}</td>
            <td>{", "|join:$workplan->profiles->getItems()}</td>
			<td>{", "|join:$workplan->authors->getItems()}</td>
			<td>{$workplan->title}</td>
        </tr>
    {/foreach}
</table>