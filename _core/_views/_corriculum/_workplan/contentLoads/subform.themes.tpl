{if ($object->topics->getCount() == 0)}
    Нет объектов для отображения
{else}
    <table class="table table-striped table-bordered table-hover table-condensed">
        <thead>
        <tr>
            <th width="16">&nbsp;</th>
            <th width="16">#</th>
            <th width="16">&nbsp;</th>
            <th>{CHtml::tableOrder("title", $object->topics->getFirstItem())}</th>
            <th>{CHtml::tableOrder("value", $object->topics->getFirstItem())}</th>
        </tr>
        </thead>
        <tbody>
        {foreach $object->topics->getItems() as $topic}
            <tr>
                <td><a href="#" class="icon-trash" onclick="if (confirm('Действительно удалить тема')) { location.href='workplancontenttopics.php?action=delete&id={$topic->getId()}'; }; return false;"></a></td>
                <td>{$topic->ordering}</td>
                <td><a href="workplancontenttopics.php?action=edit&id={$topic->getId()}" class="icon-pencil"></a></td>
                <td>{$topic->title}</td>
                <td>{$topic->value}</td>
            </tr>
        {/foreach}
        </tbody>
    </table>
{/if}