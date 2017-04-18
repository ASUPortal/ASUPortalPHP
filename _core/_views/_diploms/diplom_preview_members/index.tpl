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
        <th>{CHtml::tableOrder("kadri_id", $members->getFirstItem())}</th>
        <th>{CHtml::tableOrder("date_preview", $members->getFirstItem())}</th>
        <th>{CHtml::tableOrder("is_member", $members->getFirstItem())}</th>
        <th>{CHtml::tableOrder("comment", $members->getFirstItem())}</th>
    </tr>
    {counter start=0 print=false}
    {foreach $members->getItems() as $member}
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
            	{if ($member->is_member)}
            		Да
            	{else}
            		Нет
            	{/if}
            </td>
            <td>{$member->comment}</td>
        </tr>
    {/foreach}
</table>
{/if}
    {CHtml::paginator($paginator, "?action=index")}
{/block}

{block name="asu_right"}
	{include file="_diploms/diplom_preview_members/common.right.tpl"}
{/block}