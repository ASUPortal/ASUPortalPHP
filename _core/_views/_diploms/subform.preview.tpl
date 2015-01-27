<table class="table table-striped table-bordered table-hover table-condensed">
    <tr>
        <th>#</th>
        <th>&nbsp;</th>
        <th>Дата предзащиты</th>
        <th>% выполнения</th>
        <th>Заслушать еще раз</th>
        <th>Комиссия</th>
    </tr>
    {foreach $diplom->previews->getItems() as $preview}
        <tr>
            <td>{counter}</td>
            <td><a href="#" class="icon-trash" onclick="if (confirm('Действительно удалить предзащиту от {$preview->getPreviewDate()}')) { location.href='preview.php?action=delete&id={$preview->id}'; }; return false;"></a></td>
            <td>
                <a href="preview.php?action=edit&id={$preview->getId()}">
                    {$preview->getPreviewDate()}
                </a>
            </td>
            <td>{$preview->diplom_percent}%</td>
            <td>
                {if $preview->another_view == "1"}
                    Да
                    {else}
                    Нет
                {/if}
            </td>
            <td>
                {if !is_null($preview->commission)}
                    {$preview->commission->name}
                {/if}
            </td>
        </tr>
    {/foreach}
</table>