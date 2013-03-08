{extends file="_core.3col.tpl"}

{block name="asu_center"}
<h2>Описатели полей</h2>

<table width="100%" cellpadding="2" cellspacing="0" border="1">
    <tr>
        <th>&nbsp;</th>
        <th>#</th>
        <th>Название</th>
        <th>Описание</th>
        <th>Набор форм</th>
        <th></th>
    </tr>

    {foreach $fields->getItems() as $field}
        <tr>
            <td valign="top"><a href="#" onclick="if (confirm('Действительно удалить описатель поля {$field->title}')) { location.href='?action=delete&id={$field->id}'; }; return false;"><img src="{$web_root}images/todelete.png"></a></td>
            <td valign="top">{counter}</td>
            <td valign="top"><a href="field.php?action=edit&id={$field->id}">{$field->title}<a/> ({$field->alias})</td>
            <td valign="top">{$field->description|nl2br}</td>
            <td valign="top">{$field->formset->title}</td>
            <td><input type="checkbox" name="selected[]" value="{$field->getId()}"></td>
        </tr>
    {/foreach}
</table>

{CHtml::paginator($paginator, "?action=index")}
{/block}

{block name="asu_right"}
{include file="_print/field/index.right.tpl"}
{/block}