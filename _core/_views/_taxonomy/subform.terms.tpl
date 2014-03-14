<table class="table table-striped table-bordered table-hover table-condensed">
    <tr>
        <th align="center"></th>
        <th align="center">№</th>
        <th align="center">Значение</th>
        <th align="center">Псевдоним</th>
    </tr>

    {foreach $taxonomy->getTerms()->getItems() as $item}
        <tr class="text">
            <td><a class="icon-trash" href="?action=delete&id={$item->id}" onclick="if (!confirm('Вы действительно хотите удалить термин {$item->getValue()}?')){ return false }"></a></td>
            <td>{counter}</td>
            <td><a href="?action=editTerm&id={$item->id}">{$item->getValue()}</a></td>
            <td>{$item->getAlias()}</td>
        </tr>
    {/foreach}
</table>