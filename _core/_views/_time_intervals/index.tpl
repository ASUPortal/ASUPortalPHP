{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Учебный год</h2>

    {CHtml::helpForCurrentPage()}

    {if ($years->getCount() == 0)}
        Нет объектов для отображения
    {else}
        {include file="_core.searchLocal.tpl"}

        <table class="table table-striped table-bordered table-hover table-condensed">
            <tr>
                <th></th>
                <th>#</th>
                <th>{CHtml::tableOrder("name", $years->getFirstItem())}</th>
                <th>{CHtml::tableOrder("year.date_start", $years->getFirstItem(), true)}</th>
                <th>{CHtml::tableOrder("comment", $years->getFirstItem())}</th>
            </tr>
            {counter start=($paginator->getRecordSet()->getPageSize() * ($paginator->getCurrentPageNumber() - 1)) print=false}
            {foreach $years->getItems() as $year}
				{$trStyle=''}
				{if $year->getId() == CUtils::getCurrentYear()->getId()}
				<tr style="font-size:11pt; font-weight:bold">
				{/if}
                    <td><a class="icon-trash" href="#" onclick="if (confirm('Действительно удалить страницу {$year->name}')) { location.href='?action=delete&id={$year->id}'; }; return false;"></a></td>
                    <td>{counter}</td>
                    <td><a href="index.php?action=edit&id={$year->getId()}">{$year->name}</a></td>
                    <td>{$year->date_start} - {$year->date_end}</td>
                    <td>{$year->comment}</td>
                </tr>
            {/foreach}
        </table>

        {CHtml::paginator($paginator, "?action=index")}
    {/if}
{/block}

{block name="asu_right"}
    {include file="_time_intervals/index.right.tpl"}
{/block}