{if ($object->technologies->getCount() == 0)}
    Нет объектов для отображения
{else}
    <table class="table table-striped table-bordered table-hover table-condensed">
        <thead>
        <tr>
            <th width="16">&nbsp;</th>
            <th width="16">#</th>
            <th width="16">&nbsp;</th>
            <th>{CHtml::tableOrder("technology_id", $object->technologies->getFirstItem())}</th>
            <th>{CHtml::tableOrder("value", $object->technologies->getFirstItem())}</th>
        </tr>
        </thead>
        <tbody>
        {foreach $object->technologies->getItems() as $technology}
            <tr>
                <td><a href="#" class="icon-trash" onclick="if (confirm('Действительно удалить образовательные технологии')) { location.href='workplancontenttechnologies.php?action=delete&id={$technology->getId()}'; }; return false;"></a></td>
                <td>{$technology->ordering}</td>
                <td><a href="workplancontenttechnologies.php?action=edit&id={$technology->getId()}" class="icon-pencil"></a></td>
                <td>{$technology->technology}</td>
                <td>{$technology->value}</td>
            </tr>
        {/foreach}
        </tbody>
    </table>
{/if}