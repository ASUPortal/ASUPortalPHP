{extends file="_core.component.tpl"}

{block name="asu_center"}
    <form action="workplancontentloads.php" method="post">
    <input type="hidden" name="id" value="{$section->getId()}" />
    <input type="hidden" name="action" value="submitForm" />
    <input type="hidden" name="bean" value="{$bean->getBeanId()}" />

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
                    <td><a href="#" class="icon-trash" onclick="if (confirm('Действительно удалить нагрузка')) { location.href='workplancontentloads.php?action=delete&id={$load->getId()}'; }; return false;"></a></td>
                    <td>{$load->ordering}</td>
                    <td>{sf_toggleEdit address='workplancontentloads.php' bean=$bean element="load_{$load->getId()}" object=$section}</td>
                    <td>{sf_toggleVisible address='workplancontentloads.php' bean=$bean element="load_{$load->getId()}_details" object=$section}</td>
                    <td>{$load->loadType}</td>
                    <td>{$load->term->corriculum_discipline_section->title}</td>
                    <td>{$load->value}</td>
                </tr>
            {/sf_showIfVisible}
            {sf_showIfEditable bean=$bean element="load_{$load->getId()}"}
                <tr>
                    <td><a href="#" class="icon-trash" onclick="if (confirm('Действительно удалить нагрузка')) { location.href='workplancontentloads.php?action=delete&id={$load->getId()}'; }; return false;"></a></td>
                    <td>{sf_toggleEdit address='workplancontentloads.php' bean=$bean element="load_{$load->getId()}" object=$section}</td>
                    <td><button type="submit" name="element" value="load_{$load->getId()}" class="btn btn-link"><i class="icon-ok"></i></button></td>
                    <td>{sf_toggleVisible address='workplancontentloads.php' bean=$bean element="load_{$load->getId()}_details" object=$section}</td>
                    <td><input type="text" name="load_{$load->getId()}[load_type_id]" value="{$load->load_type_id}"></td>
                    <td><input type="text" name="load_{$load->getId()}[term_id]" value="{$load->term_id}"></td>
                    <td>{sf_input bean=$bean model=$load element="load_{$load->getId()}" attribute='value'}</td>

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
        </tbody>
    </table>
    </form>

    {$bean|var_dump}
    {CLog::dump(true)}
{/block}

{block name="asu_right"}
    {include file="_corriculum/_workplan/contentLoads/common.right.tpl"}
{/block}