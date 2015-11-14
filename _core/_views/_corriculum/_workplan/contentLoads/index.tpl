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
            <tr>
                <td><a href="#" class="icon-trash" onclick="if (confirm('Действительно удалить нагрузка')) { location.href='workplancontentloads.php?action=delete&id={$load->getId()}'; }; return false;"></a></td>
                <td>{$load->ordering}</td>
                <td><a href="workplancontentloads.php?action=edit&id={$load->getId()}" class="icon-pencil"></a></td>
                <td>{sf_toggleVisible address="workplancontentloads.php" bean=$bean element="load_{$load->getId()}" object=$section}</td>
                <td>{$load->loadType}</td>
                <td>{$load->term->corriculum_discipline_section->title}</td>
                <td>{$load->value}</td>
            </tr>
            {sf_showIfVisible bean=$bean element="load_{$load->getId()}"}
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
            {/sf_showIfVisible}
        {/foreach}
        </tbody>
    </table>

    {$bean|var_dump}
{/block}

{block name="asu_right"}
    {include file="_corriculum/_workplan/contentLoads/common.right.tpl"}
{/block}