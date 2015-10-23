{extends file="_core.component.tpl"}

{block name="asu_center"}
    {if $objects->getCount() == 0}
        Нет объектов для отображения
    {else}
        <table class="table table-striped table-bordered table-hover table-condensed">
            <thead>
            <tr>
                <th>№ занятия</th>
                <th>№ раздела</th>
                <th>Тема</th>
                <th>Число часов</th>
            </tr>
            </thead>
            <tbody>
            {foreach $objects->getItems() as $object}
                <tr>
                    <td>{counter}</td>
                    <td>{$object->load->section->sectionIndex}</td>
                    <td>{$object->title}</td>
                    <td>{$object->value}</td>
                </tr>
            {/foreach}
            </tbody>
        </table>
    {/if}
{/block}

{block name="asu_right"}
    {include file="_corriculum/_workplan/content/common.right.tpl"}
{/block}