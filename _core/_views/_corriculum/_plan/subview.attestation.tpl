<table class="table table-striped table-bordered table-hover table-condensed">
    <tr>
        <th colspan="3">Итоговая государственная аттестация</th>
    </tr>
    <tr>
        <td>Виды аттестации</td>
        <th>Длительность (недель)</th>
        <th>Длительность (з.е.)</th>
        <th>Длительность (часов)</th>
    </tr>
    {foreach $corriculum->attestations->getItems() as $p}
    <tr>
        <td>
            {if !is_null($p->type)}
                <a href="attestations.php?action=edit&id={$p->getId()}">{$p->type->getValue()}</a>
            {/if}
        </td>
        <td>{$p->length}</td>
        <td>{$p->length_credits}</td>
        <td>{$p->length_hours}</td>
    </tr>
    {/foreach}
</table>