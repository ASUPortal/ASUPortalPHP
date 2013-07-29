<table class="table table-striped table-bordered table-hover table-condensed">
    <tr>
        <th>#</th>
        <th>&nbsp;</th>
        <th>Вид практики</th>
        <th>Длительность</th>
        <th>Семестр</th>
    </tr>
    {foreach $corriculum->practices->getItems() as $p}
    <tr>
        <td>{counter}</td>
        <td><a href="#" class="icon-trash" onclick="if (confirm('Действительно удалить практику {if !is_null($p->type)}{$p->type->getValue()}{/if}')) { location.href='practices.php?action=del&id={$p->id}'; }; return false;"></a></td>
        <td>
            {if !is_null($p->type)}
                <a href="practices.php?action=edit&id={$p->getId()}">{$p->type->getValue()}</a>
            {/if}
        </td>
        <td>{$p->length}</td>
        <td>&nbsp;</td>
    </tr>
    {/foreach}
</table>