{if ($object->loads->getCount() == 0)}
    Нет объектов для отображения
{else}
    <table class="table table-striped table-bordered table-hover table-condensed">
        <thead>
        <tr>
            <th width="16">&nbsp;</th>
            <th width="16">#</th>
            <th width="16">&nbsp;</th>
            <th>{CHtml::tableOrder("load_type_id", $object->loads->getFirstItem())}</th>
            <th>{CHtml::tableOrder("term_id", $object->loads->getFirstItem())}</th>
            <th>{CHtml::tableOrder("value", $object->loads->getFirstItem())}</th>
        </tr>
        </thead>
        <tbody>
        {foreach $object->loads->getItems() as $load}
            <tr>
                <td><a href="#" class="icon-trash" onclick="if (confirm('Действительно удалить нагрузка')) { location.href='workplancontentloads.php?action=delete&id={$load->getId()}'; }; return false;"></a></td>
                <td>{counter}</td>
                <td><a href="workplancontentloads.php?action=edit&id={$load->getId()}" class="icon-pencil"></a></td>
                <td>{$load->loadType}</td>
                <td>{$load->term->corriculum_discipline_section->title}</td>
                <td>{$load->value}</td>
            </tr>
        {/foreach}
        </tbody>
    </table>
{/if}