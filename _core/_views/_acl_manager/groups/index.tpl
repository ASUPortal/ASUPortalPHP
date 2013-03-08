{extends file="_core.3col.tpl"}

{block name="asu_center"}
<h2>Управление группами доступа</h2>

    <table border="1" cellpadding="2" cellspacing="0">
        <tr>
            <th></th>
            <th>#</th>
            <th>Название</th>
            <th>Комментарий</th>
        </tr>
        {foreach $groups->getItems() as $group}
            <tr>
                <td valign="top"><a href="#" onclick="if (confirm('Действительно удалить группу {$group->name}')) { location.href='?action=delete&id={$group->id}'; }; return false;"><img src="{$web_root}images/todelete.png"></a></td>
                <td valign="top">{counter}</td>
                <td valign="top"><a href="?action=view&id={$group->id}">{$group->name}</a></td>
                <td valign="top">{$group->comment}</td>
            </tr>
        {/foreach}
    </table>

    {CHtml::paginator($paginator, "?action=index")}
{/block}

{block name="asu_right"}
{include file="_acl_manager/groups/index.right.tpl"}
{/block}