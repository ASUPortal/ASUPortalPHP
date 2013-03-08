{extends file="_core.3col.tpl"}

{block name="asu_center"}
<h2>Наборы шаблонов</h2>

<table id="dataTable" width="100%" cellspacing="0" cellpadding="2" border="1">
    <tr>
        <th>&nbsp;</th>
        <th>#</th>
        <th>Название</th>
        <th>Описание</th>
        <th>&nbsp;</th>
    </tr>
    {foreach $forms->getItems() as $set}
    <tr>
        <td valign="top"><a href="#" onclick="if (confirm('Действительно удалить набор {$set->title}')) { location.href='?action=delete&id={$set->id}'; }; return false;"><img src="{$web_root}images/todelete.png"></a></td>
        <td valign="top">{counter}</td>
        <td valign="top"><a href="?action=edit&id={$set->id}">{$set->title}</a> ({$set->alias})</td>
        <td valign="top">{$set->description|nl2br}</td>
        <td><input type="checkbox" name="selected[]" value="{$set->getId()}"></td>
    </tr>
    {/foreach}
</table>

    {CHtml::paginator($sets->getPaginator(), "?action=index")}
{/block}

{block name="asu_right"}
{include file="_print/formset/index.right.tpl"}
{/block}