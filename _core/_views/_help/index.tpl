{extends file="_core.3col.tpl"}

{block name="asu_center"}
<h2>Справочная система</h2>

<table width="100%" border="1" cellpadding="0" cellspacing="0">
    <tr>
        <th><img src="{$web_root}images/todelete.png"></th>
        <th>#</th>
        <th>Заголовок</th>
        <th>URL</th>
        <th>Текст справки</th>
    </tr>
    
    {foreach $helps->getItems() as $help}
        <tr>
            <td valign="top"><a href="#" onclick="if (confirm('Действительно удалить {$help->title}')) { location.href='?action=delete&id={$help->id}'; }; return false;"><img src="{$web_root}images/todelete.png"></a></td>
            <td valign="top">{counter}</td>
            <td valign="top"><a href="?action=edit&id={$help->id}">{$help->title}</a></td>
            <td valign="top">{$help->url}</td>
            <td valign="top">{$help->content}</td>
        </tr>
    {/foreach}
</table>

{CHtml::paginator($paginator, "?action=index")}
{/block}

{block name="asu_right"}
    {include file="_help/index.right.tpl"}
{/block}