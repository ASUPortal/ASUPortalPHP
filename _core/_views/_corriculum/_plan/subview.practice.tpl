<table class="table table-striped table-bordered table-hover table-condensed">
    <tr>
        <th colspan="3">Практика и итоговая государственная аттестация</th>
    </tr>
    <tr>
        <td>Виды практики</td>
        <td>Недель</td>
        <td>Семестр</td>
    </tr>
    {foreach $corriculum->practices->getItems() as $p}
    <tr>
        <td>
            {if !is_null($p->type)}
                <a href="practices.php?action=edit&id={$p->getId()}">{$p->type->getValue()}</a>
            {/if}
        </td>
        <td>
            {$p->length}
        </td>
        <td>&nbsp;</td>
    </tr>
    {/foreach}
</table>