{if ($load->getWorksByType(6)->getCount() == 0)}
    <div class="alert alert-block">
        Нет данных для отображения
    </div>
{else}
    <table class="table table-striped table-bordered table-hover table-condensed">
        <tr>
            <th></th>
            <th></th>
            <th>#</th>
            <th>{CHtml::tableOrder("change_section", $load->getWorksByType(6)->getFirstItem())}</th>
            <th>{CHtml::tableOrder("change_reason", $load->getWorksByType(6)->getFirstItem())}</th>
            <th>{CHtml::tableOrder("change_add_date", $load->getWorksByType(6)->getFirstItem())}</th>
            <th>{CHtml::tableOrder("is_executed", $load->getWorksByType(6)->getFirstItem())}</th>
        </tr>
        {foreach $load->getWorksByType(6)->getItems() as $work}
            <tr>
                <td>
                    <a href="work.php?action=edit&id={$work->getId()}&year={$year}">
                        <i class="icon-pencil"></i>
                    </a>
                </td>
                <td>
                    <a href="#" onclick="if (confirm('Действительно удалить запись?')) { location.href='work.php?action=delete&id={$work->getId()}'; }; return false;">
                        <i class="icon-trash"></i>
                    </a>
                </td>
                <td>{counter}</td>
                <td>{$work->change_section}</td>
                <td>{$work->change_reason}</td>
                <td>{$work->change_add_date}</td>
                <td>{$work->isExecuted()}</td>
            </tr>
        {/foreach}
    </table>
{/if}