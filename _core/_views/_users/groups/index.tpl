{extends file="_core.3col.tpl"}

{block name="asu_center"}
<h2>Группы пользователей</h2>

    {CHtml::helpForCurrentPage()}

    <table border="1" cellpadding="2" cellspacing="0">
        <tr>
            <th></th>
            <th>#</th>
            <th>{CHtml::tableOrder("comment", $groups->getFirstItem())}</th>
            <th>{CHtml::tableOrder("name", $groups->getFirstItem())}</th>
        </tr>
        {counter start=(20 * ($paginator->getCurrentPageNumber() - 1)) print=false}
        {foreach $groups->getItems() as $group}
        <tr>
            <td><a href="#" onclick="if (confirm('Действительно удалить группу {$group->getName()}')) { location.href='?action=delete&id={$group->getId()}'; }; return false;"><img src="{$web_root}images/todelete.png"></a></td>
            <td>{counter}</td>
            <td><a href="groups.php?action=edit&id={$group->getId()}">{$group->comment}</a></td>
            <td>{$group->getName()}</td>
        </tr>
        {/foreach}
    </table>

    {CHtml::paginator($paginator, "?action=index")}
{/block}

{block name="asu_right"}
{include file="_users/groups/index.right.tpl"}
{/block}