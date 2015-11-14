<tr>
    <th width="16">&nbsp;</th>
    <th width="16">#</th>
    <th width="16">&nbsp;</th>
    <th width="16">{sf_toggleVisible address="workplancontentloads.php" bean=$bean element="load_{$load->getId()}_selfeducation" object=$section}</th>
    <th colspan="3">Самостоятельное изучение</th>
</tr>
{sf_showIfVisible bean=$bean element="load_{$load->getId()}_selfeducation"}
{foreach $load->selfEducations as $education}
    <tr>
        <td widtd="16">&nbsp;</td>
        <td widtd="16">#</td>
        <td widtd="16">&nbsp;</td>
        <td widtd="16">&nbsp;</td>
        <td colspan="2">{$education->question_title}</td>
        <td colspan="2">{$education->question_hours}</td>
    </tr>
{/foreach}
{/sf_showIfVisible}