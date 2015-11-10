{if ($object->selfEducations->getCount() == 0)}
    Нет объектов для отображения
{else}
    <table class="table table-striped table-bordered table-hover table-condensed">
        <thead>
        <tr>
            <th width="16">&nbsp;</th>
            <th width="16">#</th>
            <th width="16">&nbsp;</th>
            <th>{CHtml::tableOrder("question_title", $object->selfEducations->getFirstItem())}</th>
            <th>{CHtml::tableOrder("question_hours", $object->selfEducations->getFirstItem())}</th>
        </tr>
        </thead>
        <tbody>
        {foreach $object->selfEducations->getItems() as $se}
            <tr>
                <td><a href="#" class="icon-trash" onclick="if (confirm('Действительно удалить вопрос для самостоятельного изучения')) { location.href='workplanselfeducationblocks.php?action=delete&id={$se->getId()}'; }; return false;"></a></td>
                <td>{$se->ordering}</td>
                <td><a href="workplanselfeducationblocks.php?action=edit&id={$se->getId()}" class="icon-pencil"></a></td>
                <td>{$se->question_title}</td>
                <td>{$se->question_hours}</td>
            </tr>
        {/foreach}
        </tbody>
    </table>
{/if}