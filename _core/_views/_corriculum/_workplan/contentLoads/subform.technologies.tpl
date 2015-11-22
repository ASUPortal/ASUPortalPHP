<tr>
    <th width="16">&nbsp;</th>
    <th width="16">#</th>
    <th width="16">&nbsp;</th>
    <th width="16">{sf_toggleVisible address="workplancontentloads.php" bean=$bean element="load_{$load->getId()}_technologies" object=$section}</th>
    <th colspan="3">Образовательные технологии</th>
</tr>
{sf_showIfVisible bean=$bean element="load_{$load->getId()}_technologies"}
{foreach $load->technologies as $technology}
    {sf_showByDefault bean=$bean element="technology_load_{$technology->getId()}"}
    {sf_showIfVisible bean=$bean element="technology_load_{$technology->getId()}"}
    <tr>
        <td widtd="16">{sf_toggleDelete object=$section bean=$bean model=$technology element="technology_load_{$technology->getId()}" address='workplancontentloads.php'}</td>
        <td widtd="16">#</td>
        <td widtd="16">{sf_toggleEdit address='workplancontentloads.php' bean=$bean element="technology_load_{$technology->getId()}" object=$section}</td>
        <td widtd="16">&nbsp;</td>
        <td colspan="2">{sf_text model=$technology attribute='technology'}</td>
        <td colspan="2">{sf_text model=$technology attribute='value'}</td>
    </tr>
    {/sf_showIfVisible}
    {sf_showIfEditable bean=$bean element="technology_load_{$technology->getId()}"}
    {sf_hidden bean=$bean model=$technology element="technology_load_{$technology->getId()}" attribute='load_id'}
    {sf_hidden bean=$bean model=$technology element="technology_load_{$technology->getId()}" attribute='id'}
        <tr>
            <td>&nbsp;</td>
            <td>{sf_toggleEdit address='workplancontentloads.php' bean=$bean element="technology_load_{$technology->getId()}" object=$section}</td>
            <td>{sf_submit bean=$bean element="technology_load_{$technology->getId()}"}</td>
            <td>&nbsp;</td>
            <td colspan="2">{sf_select bean=$bean model=$technology element="technology_load_{$technology->getId()}" attribute='technology_id' source='corriculum_education_technologies' class='span12'}</td>
            <td>{sf_input bean=$bean model=$technology element="technology_load_{$technology->getId()}" attribute='value' class='span12'}</td>
        </tr>
    {/sf_showIfEditable}
{/foreach}
{sf_showIfVisible bean=$bean element="technology_load_{$load->getId()}_new"}
    <tr>
        <td>{sf_toggleEdit address='workplancontentloads.php' bean=$bean load_id="{$load->getId()}" element="technology_load_{$load->getId()}_new" object=$section}</td>
        <td colspan="6">
            Добавить технологию
        </td>
    </tr>
{/sf_showIfVisible}
{sf_showIfEditable bean=$bean element="technology_load_{$load->getId()}_new"}
{sf_hidden bean=$bean model=$newTechnology element="technology_load_{$load->getId()}_new" attribute='load_id'}
    <tr>
        <td>&nbsp;</td>
        <td>{sf_toggleEdit address='workplancontentloads.php' bean=$bean element="technology_load_{$load->getId()}_new" object=$section}</td>
        <td>{sf_submit bean=$bean element="technology_load_{$load->getId()}_new"}</td>
        <td>&nbsp;</td>
        <td colspan="2">{sf_select bean=$bean model=$newTechnology element="technology_load_{$load->getId()}_new" attribute='technology_id' source='corriculum_education_technologies' class='span12'}</td>
        <td>{sf_input bean=$bean model=$newTechnology element="technology_load_{$load->getId()}_new" attribute='value' class='span12'}</td>
    </tr>
{/sf_showIfEditable}
{/sf_showIfVisible}