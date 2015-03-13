{extends file="_core.3col.tpl"}

{block name="asu_center"}
<h2>Задачи пользователей на портале</h2>

    {CHtml::helpForCurrentPage()}

    <table class="table table-striped table-bordered table-hover table-condensed">
        <tr>
            <th></th>
            <th>#</th>
            <th>{CHtml::tableOrder("name", $tasks->getFirstItem())}</th>
            <th>{CHtml::tableOrder("alias", $tasks->getFirstItem())}</th>
            <th>{CHtml::tableOrder("url", $tasks->getFirstItem())}</th>
            <th>{CHtml::tableOrder("menu_name_id", $tasks->getFirstItem())}</th>
            <th>Комментарий</th>
        </tr>
        {counter start=(20 * ($paginator->getCurrentPageNumber() - 1)) print=false}
        {foreach $tasks->getItems() as $task}
        <tr>
            <td><a href="#" class="icon-trash" onclick="if (confirm('Действительно удалить задачу {$task->getName()}')) { location.href='?action=delete&id={$task->getId()}'; }; return false;"></td>
            <td>{counter}</td>
            <td><a href="?action=edit&id={$task->getId()}">{$task->getName()}</a></td>
            <td>{$task->alias}</td>
            <td>{$task->url}</td>
            <td>
                {if !is_null($task->menu)}
                    {$task->getMenu()->getValue()}
                {/if}
            </td>
            <td>{$task->comment}</td>
        </tr>
        {/foreach}
    </table>

    {CHtml::paginator($paginator, "?action=index")}
{/block}

{block name="asu_right"}
{include file="_users/tasks/common.right.tpl"}
{/block}