{extends file="_core.3col.tpl"}

{block name="asu_center"}
<h2>Шаблоны документов</h2>

    <table class="table table-striped table-bordered table-hover table-condensed">
    <tr>
        <th>&nbsp;</th>
        <th>#</th>
        <th>Название</th>
        <th>Описание</th>
        <th>Набор форм</th>
        <th>Активен</th>
        <th></th>
    </tr>

    {foreach $forms->getItems() as $form}
        <tr>
            <td valign="top"><a class="icon-trash" href="#" onclick="if (confirm('Действительно удалить шаблон {$form->title}')) { location.href='?action=delete&id={$form->id}'; }; return false;"></a></td>
            <td valign="top">{counter}</td>
            <td valign="top"><a href="form.php?action=edit&id={$form->id}">{$form->title}<a/> ({$form->alias})</td>
            <td valign="top">{$form->description|nl2br}</td>
            <td valign="top">{$form->formset->title}</td>
            <td valign="top">{if $form->isActive == 1}Да{else}Нет{/if}</td>
            <td><input type="checkbox" name="selected[]" value="{$form->getId()}"></td>
        </tr>
    {/foreach}
</table>

{CHtml::paginator($paginator, "?action=index")}
{/block}

{block name="asu_right"}
{include file="_print/form/common.right.tpl"}
{/block}