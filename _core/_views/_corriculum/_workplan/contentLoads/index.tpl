{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Нагрузка по разделу дисциплины</h2>

    {if ($objects->getCount() == 0)}
        Нет объектов для отображения
    {else}
        <table class="table table-striped table-bordered table-hover table-condensed">
            <thead>
                <tr>
                    <th width="16">&nbsp;</th>
                    <th width="16">#</th>
                    <th width="16">&nbsp;</th>
                    <th>{CHtml::tableOrder("load_type_id", $objects->getFirstItem())}</th>
                    <th>{CHtml::tableOrder("term_id", $objects->getFirstItem())}</th>
                    <th>{CHtml::tableOrder("value", $objects->getFirstItem())}</th>
                </tr>
            </thead>
            <tbody>
            {counter start=($paginator->getRecordSet()->getPageSize() * ($paginator->getCurrentPageNumber() - 1)) print=false}
            {foreach $objects->getItems() as $object}
                <tr>
                    <td><a href="#" class="icon-trash" onclick="if (confirm('Действительно удалить нагрузка')) { location.href='workplancontentloads.php?action=delete&id={$object->getId()}'; }; return false;"></a></td>
                    <td>{counter}</td>
                    <td><a href="workplancontentloads.php?action=edit&id={$object->getId()}" class="icon-pencil"></a></td>
                    <td>{$object->loadType}</td>
                    <td>{$object->term->corriculum_discipline_section->title}</td>
                    <td>{$object->value}</td>
                </tr>
            {/foreach}
            </tbody>
        </table>

        {CHtml::paginator($paginator, "workplancontentloads.php?action=index")}
    {/if}
{/block}

{block name="asu_right"}
    {include file="_corriculum/_workplan/contentLoads/common.right.tpl"}
{/block}