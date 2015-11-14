<tr>
    <th width="16">&nbsp;</th>
    <th width="16">#</th>
    <th width="16">&nbsp;</th>
    <th width="16">{sf_toggleVisible address="workplancontentloads.php" bean=$bean element="load_{$load->getId()}_technologies" object=$section}</th>
    <th colspan="3">Образовательные технологии</th>
</tr>
{sf_showIfVisible bean=$bean element="load_{$load->getId()}_technologies"}
{foreach $load->technologies as $technology}
    <tr>
        <td widtd="16">&nbsp;</td>
        <td widtd="16">#</td>
        <td widtd="16">&nbsp;</td>
        <td widtd="16">&nbsp;</td>
        <td colspan="2">{$technology->technology}</td>
        <td colspan="2">{$technology->value}</td>
    </tr>
{/foreach}
{/sf_showIfVisible}