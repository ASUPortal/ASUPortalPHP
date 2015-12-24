{extends file="_core.component.tpl"}

{block name="asu_center"}
    <form action="workplancontentloads.php" method="post">
    <input type="hidden" name="id" value="{$section->getId()}" />
    <input type="hidden" name="action" value="submitForm" />
    <input type="hidden" name="bean" value="{$bean->getBeanId()}" />
	{CHtml::warningSummary($section)}
    <table class="table">
        <thead>
        <tr>
            <th width="16">&nbsp;</th>
            <th width="16">#</th>
            <th width="16">&nbsp;</th>
            <th width="16">&nbsp;</th>
            <th>{CHtml::tableOrder("load_type_id", $section->loads->getFirstItem())}</th>
            <th>{CHtml::tableOrder("term_id", $section->loads->getFirstItem())}</th>
            <th>{CHtml::tableOrder("value", $section->loads->getFirstItem())}</th>
        </tr>
        </thead>
        <tbody>
        {foreach $section->loads->getItems() as $load}
            {sf_showIfVisible bean=$bean element="load_{$load->getId()}"}
                {sf_showByDefault bean=$bean element="load_{$load->getId()}"}
                <tr>
                    <td>{sf_toggleDelete object=$section bean=$bean model=$load element="load_{$load->getId()}" address='workplancontentloads.php'}</td>
                    <td>{sf_text model=$load attribute='ordering'}</td>
                    <td>{sf_toggleEdit address='workplancontentloads.php' bean=$bean element="load_{$load->getId()}" object=$section}</td>
                    <td>{sf_toggleVisible address='workplancontentloads.php' bean=$bean element="load_{$load->getId()}_details" object=$section}</td>
                    <td>{sf_text model=$load attribute='loadType'}</td>
                    <td>{sf_text model=$load attribute='term'}</td>
                    <td>{sf_text model=$load attribute='value'}</td>
                </tr>
            {/sf_showIfVisible}
            {sf_showIfEditable bean=$bean element="load_{$load->getId()}"}
                <tr>
                    <td>&nbsp;</td>
                    <td>{sf_toggleEdit address='workplancontentloads.php' bean=$bean element="load_{$load->getId()}" object=$section}</td>
                    <td>{sf_submit bean=$bean element="load_{$load->getId()}"}</td>
                    <td>{sf_toggleVisible address='workplancontentloads.php' bean=$bean element="load_{$load->getId()}_details" object=$section}</td>
                    <td>{sf_select bean=$bean model=$load element="load_{$load->getId()}" attribute='load_type_id' source='corriculum_labor_types' class='span12'}</td>
                    <td>{sf_select bean=$bean model=$load element="load_{$load->getId()}" attribute='term_id' source='class.CSearchCatalogWorkPlanTerms' class='span12' params=["plan_id" => $section->category->plan_id]}</td>
                    <td>{sf_input bean=$bean model=$load element="load_{$load->getId()}" attribute='value' class='span12'}</td>

                    {sf_hidden bean=$bean model=$load element="load_{$load->getId()}" attribute='section_id'}
                    {sf_hidden bean=$bean model=$load element="load_{$load->getId()}" attribute='id'}
                </tr>
            {/sf_showIfEditable}
            {sf_showIfVisible bean=$bean element="load_{$load->getId()}_details"}
                {include file="_corriculum/_workplan/contentLoads/subform.themes.tpl"}
                {include file="_corriculum/_workplan/contentLoads/subform.technologies.tpl"}
                {include file="_corriculum/_workplan/contentLoads/subform.selfeducation.tpl"}
            {/sf_showIfVisible}
        {/foreach}
        {sf_showIfVisible bean=$bean element='load_new'}
        <tr>
            <td>{sf_toggleEdit address='workplancontentloads.php' bean=$bean element="load_new" object=$section}</td>
            <td colspan="6">
                Добавить вид нагрузки
            </td>
        </tr>
        {/sf_showIfVisible}
        {sf_showIfEditable bean=$bean element='load_new'}
        {sf_hidden bean=$bean model=$newLoad element='load_new' attribute='section_id'}
            <tr>
                <td>&nbsp;</td>
                <td>{sf_toggleEdit address='workplancontentloads.php' bean=$bean element="load_new" object=$section}</td>
                <td>{sf_submit bean=$bean element='load_new'}</td>
                <td>&nbsp;</td>
                <td>{sf_select bean=$bean model=$newLoad element='load_new' attribute='load_type_id' source='corriculum_labor_types' class='span12'}</td>
                <td>{sf_select bean=$bean model=$newLoad element='load_new' attribute='term_id' source='class.CSearchCatalogWorkPlanTerms' class='span12' params=["plan_id" => $section->category->plan_id]}</td>
                <td>{sf_input bean=$bean model=$newLoad element='load_new' attribute='value' class='span12'}</td>
            </tr>
        {/sf_showIfEditable}
        </tbody>
    </table>
    </form>

    {CLog::dump()}
{/block}

{block name="asu_right"}
    {include file="_corriculum/_workplan/contentLoads/common.right.tpl"}
{/block}
