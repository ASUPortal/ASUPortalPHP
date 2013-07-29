{if $model->fields->getCount() == 0}
    Нет полей у модели
{else}
<table class="table table-striped table-bordered table-hover table-condensed">
    <tr>
        <th></th>
        <th>#</th>
        <th>{CHtml::tableOrder("field_name", $model->fields->getFirstItem())}</th>
    </tr>
    {foreach $model->fields->getItems() as $field}
    <tr>
        <td><a href="#" class="icon-trash" onclick="if (confirm('Действительно удалить поле {$field->field_name}')) { location.href='fields.php?action=delete&id={$field->id}'; }; return false;"></a></td>
        <td>{counter}</td>
        <td>
            <a href="fields.php?action=edit&id={$field->getId()}">
                {$field->field_name}
            </a>
        </td>
    </tr>
    {/foreach}
</table>
{/if}