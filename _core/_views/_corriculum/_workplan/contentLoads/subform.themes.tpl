<tr>
    <th width="16">&nbsp;</th>
    <th width="16">#</th>
    <th width="16">&nbsp;</th>
    <th width="16">{sf_toggleVisible address="workplancontentloads.php" bean=$bean element="load_{$load->getId()}_themes" object=$section}</th>
    <th colspan="3">Темы</th>
</tr>
{sf_showIfVisible bean=$bean element="load_{$load->getId()}_themes"}
{foreach $load->topics as $topic}
    <tr>
        <td widtd="16">&nbsp;</td>
        <td widtd="16">#</td>
        <td widtd="16">&nbsp;</td>
        <td widtd="16">&nbsp;</td>
        <td colspan="2">{$topic->title}</td>
        <td colspan="2">{$topic->value}</td>
    </tr>
{/foreach}
{/sf_showIfVisible}