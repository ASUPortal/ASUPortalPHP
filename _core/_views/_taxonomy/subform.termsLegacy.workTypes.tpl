<table cellpadding="0" cellspacing="0" border="1" width="99%">
    <tr class="text">
        <th align="center"></th>
        <th align="center">№</th>
        <th align="center">Значение</th>
        <th align="center">Псевдоним</th>
        <th align="center">Учитывать в итогах</th>
    </tr>

    {foreach $taxonomy->getTerms()->getItems() as $item}
        <tr class="text" bgcolor="#DFEFFF">
            <td><a class="icon-trash" href="?action=deleteLegacyTerm&id={$item->id}&taxonomy_id={$taxonomy->getId()}" onclick="if (!confirm('Вы действительно хотите удалить термин {$item->getValue()}?')){ return false }"></a></td>
            <td>{counter}</td>
            <td><a href="?action=editLegacyTerm&id={$item->id}&taxonomy_id={$taxonomy->getId()}">{$item->getValue()}</a></td>
            <td>{$item->name_hours_kind}</td>
            <td>
                {if $item->is_total == "1"}
                    Да
                {else}
                    Нет
                {/if}
            </td>
        </tr>
    {/foreach}
</table>