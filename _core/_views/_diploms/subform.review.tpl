<table border="1" cellpadding="2" cellspacing="0" width="100%">
    <tr>
        <th>#</th>
        <th>&nbsp;</th>
        <th>Дата предзащиты</th>
        <th>Процент выполнения</th>
        <th>Заслушать еще раз</th>
        <th>Комиссия</th>
        <th>Комментарий</th>
    </tr>
    {foreach $diplom->previews->getItems() as $preview}
    <tr>
        <td>{$counter}</td>
        <td></td>
        <td><a href="previews.php?action=edit&id={$preview->getId()}">{$preview->date_preview|date_format:"d.m.Y"}</a></td>
        <td>{$preview->diplom_percent}%</td>
        <td>
            {if $preview->anotder_view == 0}
                нет
            {else}
                да
            {/if}
        </td>
        <td>

        </td>
        <td>{$preview->comment}</td>
    </tr>
    {/foreach}
</table>