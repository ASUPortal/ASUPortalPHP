{if isset($object)}
    {$module = $object}
{/if}
{if $module->sections->getCount() > 0}
    <table class="table table-striped table-bordered table-hover table-condensed">
        <thead>
        <tr>
            <th width="16">&nbsp;</th>
            <th width="16">#</th>
            <th width="16">&nbsp;</th>
            <th>{CHtml::tableOrder("sectionIndex", $module->sections->getFirstItem())}</th>
            <th>{CHtml::tableOrder("name", $module->sections->getFirstItem())}</th>
            <th>{CHtml::tableOrder("content", $module->sections->getFirstItem())}</th>
            <th>Формы текущего контроля</th>
        </tr>
        </thead>
        <tbody>
        {foreach $module->sections->getItems() as $section}
            <tr>
                <td><a href="#" class="icon-trash" onclick="if (confirm('Действительно удалить раздел')) { location.href='workplancontentsections.php?action=delete&id={$section->getId()}'; }; return false;"></a></td>
                <td>{counter}</td>
                <td><a href="workplancontentsections.php?action=edit&id={$section->getId()}" class="icon-pencil"></a></td>
                <td>{$section->sectionIndex}</td>
                <td>{$section->name}</td>
                <td>{$section->content|nl2br}</td>
                <td>{", "|join:$section->controls->getItems()}</td>
            </tr>
        {/foreach}
        </tbody>
    </table>
{else}
    Нет объектов для отображения
{/if}