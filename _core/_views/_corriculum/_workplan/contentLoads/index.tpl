{extends file="_core.component.tpl"}

{block name="asu_center"}
    Пока это просто вывод нагрузки

    <table class="table table-striped table-bordered table-hover table-condensed">
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
                    <td>{sf_toggleEdit address='workplancontentloads.php' bean=$bean element="load_{$load->getId()}" object=$section}</td>
                    <td colspan="6">Я форма</td>
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

    {$bean|var_dump}
    {CLog::dump(true)}
{/block}

{block name="asu_right"}
    {include file="_corriculum/_workplan/contentLoads/common.right.tpl"}
{/block}