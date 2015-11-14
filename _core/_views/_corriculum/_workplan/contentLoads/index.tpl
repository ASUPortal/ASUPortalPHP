{extends file="_core.component.tpl"}

{block name="asu_center"}
    Пока это просто вывод нагрузки

    <table class="table table-striped table-bordered table-hover table-condensed">
        <thead>
        <tr>
            <th width="16">&nbsp;</th>
            <th width="16">#</th>
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
                <td>{$load->loadType}</td>
                <td>{$load->term->corriculum_discipline_section->title}</td>
                <td>{$load->value}</td>
            </tr>
        {/foreach}
        </tbody>
    </table>
{/block}

{block name="asu_right"}
    {include file="_corriculum/_workplan/contentLoads/common.right.tpl"}
{/block}