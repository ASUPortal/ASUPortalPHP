<table class="table table-striped table-bordered table-hover table-condensed">
    <tr>
        <th colspan="3">Практики</th>
    </tr>
    <tr>
        <td>Виды практики</td>
        <th>Длительность (недель)</th>
        <th>Длительность (з.е.)</th>
        <th>Длительность (часов)</th>
    </tr>
    {foreach $corriculum->practices->getItems() as $p}
    <tr>
        <td>
            {if !is_null($p->type)}
                <a href="practices.php?action=edit&id={$p->getId()}">{$p->type->getValue()}</a>
            {/if}
        </td>
        <td>{$p->length}</td>
        <td>{$p->length_credits}</td>
        <td>{$p->length_hours}</td>
    </tr>
    {/foreach}
</table>