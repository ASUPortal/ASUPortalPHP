{if ($plan->selfEducations->getCount() == 0)}
    Нет объектов для отображения
{else}
    <table class="table table-striped table-bordered table-hover table-condensed">
        <thead>
        <tr>
            <th width="16">&nbsp;</th>
            <th width="16">#</th>
            <th width="16">&nbsp;</th>
            <th>{CHtml::tableOrder("question_title", $plan->selfEducations->getFirstItem())}</th>
            <th>{CHtml::tableOrder("question_hours", $plan->selfEducations->getFirstItem())}</th>
        </tr>
        </thead>
        <tbody>
        {foreach $plan->selfEducations->getItems() as $object}
            <tr>
                <td><a href="#" class="icon-trash" onclick="if (confirm('Действительно удалить вопрос для самостоятельного изучения')) { location.href='workplanselfeducationblocks.php?action=delete&id={$object->getId()}'; }; return false;"></a></td>
                <td>{counter}</td>
                <td><a href="workplanselfeducationblocks.php?action=edit&id={$object->getId()}" class="icon-pencil"></a></td>
                <td>{$object->question_title}</td>
                <td>{$object->question_hours}</td>
            </tr>
        {/foreach}
        </tbody>
    </table>
{/if}