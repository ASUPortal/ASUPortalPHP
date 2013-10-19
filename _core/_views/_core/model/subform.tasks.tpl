{if $model->tasks->getCount() == 0}
С моделью не связаны никакие задачи
{else}
    <table class="table table-striped table-bordered table-hover table-condensed">
        <tr>
            <th></th>
            <th>#</th>
            <th>{CHtml::tableOrder("task_id", $model->tasks->getFirstItem())}</th>
        </tr>
        {foreach $model->taskModels->getItems() as $task}
            <tr>
                <td><a href="#" class="icon-trash" onclick="if (confirm('Действительно удалить задачу {$task->task->name}')) { location.href='tasks.php?action=delete&id={$task->id}'; }; return false;"></a></td>
                <td>{counter}</td>
                <td>
                    <a href="tasks.php?action=edit&id={$task->getId()}">
                        {$task->task->name}
                    </a>
                </td>
            </tr>
        {/foreach}
    </table>
{/if}