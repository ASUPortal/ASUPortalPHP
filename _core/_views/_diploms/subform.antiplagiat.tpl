{if $diplom->antiplagiatChecks->getCount() == 0}
	Нет данных для отображения
{else}
<table class="table table-striped table-bordered table-hover table-condensed">
    <tr>
        <th>#</th>
        <th>&nbsp;</th>
        <th>{CHtml::tableOrder("check_date", $diplom->antiplagiatChecks->getFirstItem())}</th>
        <th>{CHtml::tableOrder("check_time", $diplom->antiplagiatChecks->getFirstItem())}</th>
        <th>{CHtml::tableOrder("borrowing_percent", $diplom->antiplagiatChecks->getFirstItem())}</th>
        <th>{CHtml::tableOrder("citations_percent", $diplom->antiplagiatChecks->getFirstItem())}</th>
        <th>{CHtml::tableOrder("originality_percent", $diplom->antiplagiatChecks->getFirstItem())}</th>
        <th>{CHtml::tableOrder("comments", $diplom->antiplagiatChecks->getFirstItem())}</th>
        <th>{CHtml::tableOrder("responsible_id", $diplom->antiplagiatChecks->getFirstItem())}</th>
    </tr>
    {counter start=0 print=false}
    {foreach $diplom->antiplagiatChecks->getItems() as $check}
        <tr>
            <td>{counter}</td>
            <td><a href="#" class="icon-trash" onclick="if (confirm('Действительно удалить проверку')) { location.href='antiplagiat.php?action=delete&id={$check->id}'; }; return false;"></a></td>
            <td>
                <a href="antiplagiat.php?action=edit&id={$check->getId()}">
                    {$check->check_date|date_format:"d.m.Y"}
                </a>
            </td>
            <td>{$check->check_time}</td>
            <td>{$check->borrowing_percent}</td>
            <td>{$check->citations_percent}</td>
            <td>{$check->originality_percent}</td>
            <td>{$check->comments}</td>
            <td>{$check->responsible->getNameShort()}</td>
        </tr>
    {/foreach}
</table>
{/if}