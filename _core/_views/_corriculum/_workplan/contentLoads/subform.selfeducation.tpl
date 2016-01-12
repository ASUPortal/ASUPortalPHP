<tr>
    <th width="16">&nbsp;</th>
    <th width="16">#</th>
    <th width="16">&nbsp;</th>
    <th width="16">{sf_toggleVisible address="workplancontentloads.php" bean=$bean element="load_{$load->getId()}_selfeducation" object=$section}</th>
    <th colspan="3" bgcolor="lightblue">Самостоятельное изучение</th>
</tr>
{sf_showIfVisible bean=$bean element="load_{$load->getId()}_selfeducation"}
{foreach $load->selfEducations as $education}
    {sf_showByDefault bean=$bean element="education_load_{$education->getId()}"}
    {sf_showIfVisible bean=$bean element="education_load_{$education->getId()}"}
    <tr>
        <td widtd="16">{sf_toggleDelete object=$section bean=$bean model=$education element="education_load_{$education->getId()}" address='workplancontentloads.php'}</td>
        <td widtd="16">#</td>
        <td widtd="16">{sf_toggleEdit address='workplancontentloads.php' bean=$bean element="education_load_{$education->getId()}" object=$section}</td>
        <td widtd="16">&nbsp;</td>
        <td colspan="2" bgcolor="whitesmoke">{sf_text model=$education attribute='question_title'}</td>
        <td colspan="2" bgcolor="wheat">{sf_text model=$education attribute='question_hours'}</td>
    </tr>
    {/sf_showIfVisible}
    {sf_showIfEditable bean=$bean element="education_load_{$education->getId()}"}
    {sf_hidden bean=$bean model=$education element="education_load_{$education->getId()}" attribute='load_id'}
    {sf_hidden bean=$bean model=$education element="education_load_{$education->getId()}" attribute='plan_id'}
    {sf_hidden bean=$bean model=$education element="education_load_{$education->getId()}" attribute='id'}
        <tr>
            <td>&nbsp;</td>
            <td>{sf_toggleEdit address='workplancontentloads.php' bean=$bean element="education_load_{$education->getId()}" object=$section}</td>
            <td>{sf_submit bean=$bean element="education_load_{$education->getId()}"}</td>
            <td>&nbsp;</td>
            <td colspan="2">{sf_input bean=$bean model=$education element="education_load_{$education->getId()}" attribute='question_title' class='span12'}</td>
            <td>{sf_input bean=$bean model=$education element="education_load_{$education->getId()}" attribute='question_hours' class='span12'}</td>
        </tr>
    {/sf_showIfEditable}
{/foreach}
{sf_showIfVisible bean=$bean element="education_load_{$load->getId()}_new"}
    <tr>
        <td>{sf_toggleEdit address='workplancontentloads.php' bean=$bean load_id="{$load->getId()}" element="education_load_{$load->getId()}_new" object=$section}</td>
        <td colspan="6">
            Добавить вопрос для самостоятельного изучения
        </td>
    </tr>
{/sf_showIfVisible}
{sf_showIfEditable bean=$bean element="education_load_{$load->getId()}_new"}
    {sf_hidden bean=$bean model=$newEducation element="education_load_{$load->getId()}_new" attribute='load_id'}
    {sf_hidden bean=$bean model=$newEducation element="education_load_{$load->getId()}_new" attribute='plan_id'}
    <tr>
        <td>&nbsp;</td>
        <td>{sf_toggleEdit address='workplancontentloads.php' bean=$bean element="education_load_{$load->getId()}_new" object=$section}</td>
        <td>{sf_submit bean=$bean element="education_load_{$load->getId()}_new"}</td>
        <td>&nbsp;</td>
        <td colspan="2">{sf_input bean=$bean model=$newEducation element="education_load_{$load->getId()}_new" attribute='question_title' class='span12'}</td>
        <td>{sf_input bean=$bean model=$newEducation element="education_load_{$load->getId()}_new" attribute='question_hours' class='span12'}</td>
    </tr>
{/sf_showIfEditable}
{/sf_showIfVisible}