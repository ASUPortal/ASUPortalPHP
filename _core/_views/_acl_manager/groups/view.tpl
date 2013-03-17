{extends file="_core.3col.tpl"}

{block name="asu_center"}
<h2>Просмотр группы {$group->name}</h2>
    <p>{$group->comment}</p>
    {if !is_null($group->parent)}
        <p><b>Родительская группа:</b> <a href="{$group->parent->getId()}">{$group->parent->name}</a></p>
    {/if}

    <p>Состав группы</p>
    <table border="1" cellpadding="2" cellspacing="0">
        <tr>
            <th width="16"></th>
            <th width="16">#</th>
            <th width="16"></th>
            <th>Название</th>
        </tr>
        {foreach $group->childGroups->getItems() as $child}
            <tr>
                <td valign="top">&nbsp;</td>
                <td valign="top">{counter}</td>
                <td valign="top"><center><img src="{$web_root}images/{$icon_theme}/16x16/apps/system-users.png" align="center"></center></td>
                <td valign="top">{$child->name}</td>
            </tr>
        {/foreach}
        {foreach $group->users->getItems() as $user}
            <tr>
                <td valign="top"><a href="#" onclick="if (confirm('Действительно удалить пользователя {$user->getName()} из группы')) { location.href='?action=delete&id={$user->getId()}'; }; return false;"><img src="{$web_root}images/todelete.png"></a></td>
                <td valign="top">{counter}</td>
                <td valign="top"><center><img src="{$web_root}images/{$icon_theme}/16x16/apps/preferences-desktop-theme.png"></center></td>
                <td valign="top">{$user->getName()}</td>
            </tr>
        {/foreach}
    </table>
{/block}

{block name="asu_right"}
{include file="_acl_manager/groups/view.right.tpl"}
{/block}