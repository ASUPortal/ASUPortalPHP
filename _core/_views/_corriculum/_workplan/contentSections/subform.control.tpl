{if ($object->final_control->getCount() == 0)}
    Нет объектов для отображения
{else}
    <table class="table table-striped table-bordered table-hover table-condensed">
        <thead>
        <tr>
            <th width="16">&nbsp;</th>
            <th width="16">#</th>
            <th width="16">&nbsp;</th>
            <th>{CHtml::tableOrder("control_type_id", $object->final_control->getFirstItem())}</th>
            <th>{CHtml::tableOrder("term_id", $object->loads->getFirstItem())}</th>
        </tr>
        </thead>
        <tbody>
        {foreach $object->final_control->getItems() as $control}
            <tr>
                <td><a href="#" class="icon-trash" onclick="if (confirm('Действительно удалить вид контроля')) { location.href='workplancontentfinalcontrol.php?action=delete&id={$control->getId()}'; }; return false;"></a></td>
                <td>{counter}</td>
                <td><a href="workplancontentfinalcontrol.php?action=edit&id={$control->getId()}" class="icon-pencil"></a></td>
                <td>{$control->controlType}</td>
                <td>{$control->term}</td>
            </tr>
        {/foreach}
        </tbody>
    </table>
{/if}