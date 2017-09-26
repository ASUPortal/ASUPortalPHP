{if ($settings->reports->getCount() == 0)}
    Нет объектов для отображения
{else}
<table class="table table-striped table-bordered table-hover table-condensed">
    <thead>
    <tr>
        <th width="16">&nbsp;</th>
        <th width="16">#</th>
        <th width="16">&nbsp;</th>
        <th>{CHtml::tableOrder("title", $settings->reports->getFirstItem())}</th>
    </tr>
    </thead>
    <tbody>
        {foreach $settings->reports->getItems() as $report}
            <tr>
                <td><a href="#" class="icon-trash" onclick="if (confirm('Действительно удалить отчет?')) { location.href='reports.php?action=delete&id={$report->getId()}'; }; return false;"></a></td>
                <td>{counter}</td>
                <td><a href="reports.php?action=edit&id={$report->getId()}" class="icon-pencil"></a></td>
                <td>{$report->report->title}</td>
            </tr>
        {/foreach}
    </tbody>
    </table>
{/if}