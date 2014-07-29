<table class="table table-striped table-bordered table-hover table-condensed">
    <tr>
        <th>#</th>
        <th>&nbsp;</th>
        <th>Вид аттестации</th>
        <th>Длительность (недель)</th>
        <th>Длительность (з.е.)</th>
        <th>Длительность (часов)</th>
        <th>Семестр</th>
    </tr>
    {foreach $corriculum->attestations->getItems() as $p}
    <tr>
        <td>{counter}</td>
        <td><a href="#" class="icon-trash" onclick="if (confirm('Действительно удалить аттестацию {if !is_null($p->type)}{$p->type->getValue()}{/if}')) { location.href='attestations.php?action=delete&id={$p->id}'; }; return false;"></a></td>
        <td>
            {if !is_null($p->type)}
                <a href="attestations.php?action=edit&id={$p->getId()}">{$p->type->getValue()}</a>
            {/if}
        </td>
        <td>{$p->length}</td>
        <td>{$p->length_credits}</td>
        <td>{$p->length_hours}</td>
        <td>&nbsp;</td>
    </tr>
    {/foreach}
</table>