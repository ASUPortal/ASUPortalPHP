{extends file="_core.component.tpl"}

{block name="asu_center"}
    {if $objects->getCount() == 0}
        Нет объектов для отображения
    {else}
        <table class="table table-striped table-bordered table-hover table-condensed">
            <thead>
            <tr>
                <th>№ л/р</th>
                <th>№ раздела</th>
                <th>Наименование лабораторных работ</th>
                <th>Число часов</th>
            </tr>
            </thead>
            <tbody>
            {foreach $objects->getItems() as $term_id=>$termData}
                <tr>
                    <td colspan="4">
                        {CBaseManager::getWorkPlanTerm($term_id)}
                    </td>
                </tr>
                {foreach $termData as $lab}
                    <tr>
                        <td>{counter}</td>
                        <td>{$lab->load->section->sectionIndex}</td>
                        <td>{$lab->title}</td>
                        <td>{$lab->value}</td>
                    </tr>
                {/foreach}
            {/foreach}
            </tbody>
        </table>
    {/if}
{/block}

{block name="asu_right"}
    {include file="_corriculum/_workplan/content/common.right.tpl"}
{/block}