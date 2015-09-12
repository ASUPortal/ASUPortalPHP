{if ($plan->tasks->getCount() == 0)}
    Нет объектов для отображения
{else}
    <table class="table table-striped table-bordered table-hover table-condensed">
        <thead>
        <tr>
            <th width="16">&nbsp;</th>
            <th width="16">#</th>
            <th width="16">&nbsp;</th>
            <th>{CHtml::tableOrder("task", $plan->tasks->getFirstItem())}</th>
        </tr>
        </thead>
        <tbody>
        {foreach $plan->tasks->getItems() as $object}
            <tr>
                <td><a href="#" class="icon-trash" onclick="if (confirm('Действительно удалить задача рабочей программы')) { location.href='workplantasks.php?action=delete&id={$object->getId()}'; }; return false;"></a></td>
                <td>{counter}</td>
                <td><a href="workplantasks.php?action=edit&id={$object->getId()}" class="icon-pencil"></a></td>
                <td>{$object->task}</td>
            </tr>
        {/foreach}
        </tbody>
    </table>
{/if}