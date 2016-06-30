{if ($discipline->plans->getCount() == 0)}
    Нет рабочих программ для отображения
{else}
    <table class="table table-striped table-bordered table-hover table-condensed">
        <tr>
            <th>#</th>
            <th>&nbsp;</th>
            <th>Наименование</th>
        </tr>
        {counter start=0 print=false}
        {foreach $discipline->plans->getItems() as $plan}
            <tr>
                <td>{counter}</td>
                <td><a href="#" class="icon-trash" onclick="if (confirm('Действительно удалить рабочую программу?')) { location.href='workplans.php?action=delete&id={$plan->getId()}'; }; return false;"></a></td>
                <td>
                	<a href="workplans.php?action=edit&id={$plan->getId()}">{$plan->title_display}, дата: {$plan->date_of_formation|date_format:"d.m.Y"}, автор: {", "|join:$plan->authors->getItems()}</a>
                </td>
            </tr>
        {/foreach}
    </table>
{/if}