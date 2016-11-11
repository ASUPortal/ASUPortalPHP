{extends file="_core.component.tpl"}

{block name="asu_center"}
    {if ($objects->getCount() == 0)}
        Нет объектов для отображения
    {else}
        <table class="table table-striped table-bordered table-hover table-condensed">
            <thead>
                <tr>
                    <th width="16">#</th>
                    <th width="90">№ раздела</th>
                    <th>Вопрос</th>
                    <th>Количество часов</th>
                </tr>
            </thead>
            <tbody>
            {foreach $objects->getItems() as $object}
                <tr>
                    <td>{$object->ordering}</td>
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