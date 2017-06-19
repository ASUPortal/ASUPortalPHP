{extends file="_core.component.tpl"}

{block name="asu_center"}
<h2>Члены комиссии</h2>

    {CHtml::helpForCurrentPage()}
    
{if $members->getCount() == 0}
	Нет данных для отображения
{else}
<table class="table table-striped table-bordered table-hover table-condensed">
    <tr>
        <th>#</th>
        <th>&nbsp;</th>
        <th>{CHtml::tableOrder("person_id", $members->getFirstItem())}</th>
        <th>{CHtml::tableOrder("date_preview", $members->getFirstItem())}</th>
        <th>{CHtml::tableOrder("is_visited", $members->getFirstItem())}</th>
        <th>{CHtml::tableOrder("not_visited_reason", $members->getFirstItem())}</th>
    </tr>
    {counter start=($paginator->getRecordSet()->getPageSize() * ($paginator->getCurrentPageNumber() - 1)) print=false}
    {foreach $members->getItems() as $member}
        {if ($member->person_id != 0)}
        <tr>
            <td>{counter}</td>
            <td><a href="#" class="icon-trash" onclick="if (confirm('Действительно удалить члена комиссии')) { location.href='members.php?action=delete&id={$member->id}'; }; return false;"></a></td>
            <td>
                <a href="members.php?action=edit&id={$member->getId()}">
                    {$member->person->getName()}
                </a>
            </td>
            <td>{$member->date_preview|date_format:"d.m.Y"}</td>
            <td>
            	{if ($member->is_visited)}
            		Явка
            	{else}
            		Неявка
            	{/if}
            </td>
            <td>{$member->not_visited_reason}</td>
        </tr>
        {/if}
    {/foreach}
</table>
{/if}
    {CHtml::paginator($paginator, "members.php?action=index")}
{/block}

{block name="asu_right"}
	{include file="_state_attestation/members/common.right.tpl"}
{/block}