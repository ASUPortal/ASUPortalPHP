{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Справочник видов работ</h2>
    {CHtml::helpForCurrentPage()}

    {if $works->getCount() == 0}
        <div class="alert">
            Нет документов для отображения
        </div>
    {else}
    {include file="_core.search.tpl"}

    <table class="table table-striped table-bordered table-hover table-condensed">
        <tr>
        	{if (CSession::getCurrentUser()->getLevelForCurrentTask() == 1 or CSession::getCurrentUser()->getLevelForCurrentTask() == 3)}
            {else}
            <th>&nbsp;</th>
            {/if}
            <th>#</th>
            <th>{CHtml::tableOrder("name", $works->getFirstItem())}</th>
            <th>{CHtml::tableOrder("time_norm", $works->getFirstItem())}</th>
            <th>{CHtml::tableOrder("comment", $works->getFirstItem())}</th>
        </tr>
        {counter start=($paginator->getRecordSet()->getPageSize() * ($paginator->getCurrentPageNumber() - 1)) print=false}
        {foreach $works->getItems() as $work}
            <tr>
            {if (CSession::getCurrentUser()->getLevelForCurrentTask() == 1 or CSession::getCurrentUser()->getLevelForCurrentTask() == 3)}
            {else}
                <td><a href="#" class="icon-trash" onclick="if (confirm('Действительно удалить {$work->name}')) { location.href='?action=delete&id={$work->getId()}'; }; return false;"></a></td>
            {/if}
                <td>{counter}</td>
                <td>
                	{if (CSession::getCurrentUser()->getLevelForCurrentTask() == 1 or CSession::getCurrentUser()->getLevelForCurrentTask() == 3)}
                		{$work->name}
            		{else}
                    <a href="worktypes.php?action=edit&id={$work->getId()}">
                        {$work->name}
                    </a>
                    {/if}
                </td>
                <td>{$work->time_norm}</td>
                <td>{$work->comment}</td>
            </tr>
        {/foreach}
    </table>

    {CHtml::paginator($paginator, "?action=index")}
    {/if}
{/block}

{block name="asu_right"}
    {include file="_individual_plan/worktypes/index.right.tpl"}
{/block}