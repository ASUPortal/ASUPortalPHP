<table cellpadding="0" cellspacing="0" border="1" width="100%">
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
            <td><a href="#" onclick="if (confirm('Действительно удалить предзащиту от {$preview->getPreviewDate()}')) { location.href='previews.php?action=delete&id={$preview->id}'; }; return false;"><img src="{$web_root}images/todelete.png"></a></td>
            <td>
                <a href="previews.php?action=edit&id={$preview->getId()}">
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