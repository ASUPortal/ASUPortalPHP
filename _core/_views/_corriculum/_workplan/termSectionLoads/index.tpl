{extends file="_core.component.tpl"}

{block name="asu_center"}
    {if ($objects->getCount() == 0)}
        Нет объектов для отображения
    {else}
        <table class="table table-striped table-bordered table-hover table-condensed">
            <thead>
                <tr>
                    <th width="16">&nbsp;</th>
                    <th width="16">#</th>
                    <th width="16">&nbsp;</th>
                    <th>{CHtml::tableOrder("id", $objects->getFirstItem())}</th>
<th>{CHtml::tableOrder("section_id", $objects->getFirstItem())}</th>
<th>{CHtml::tableOrder("type_id", $objects->getFirstItem())}</th>
<th>{CHtml::tableOrder("value", $objects->getFirstItem())}</th>
                </tr>
            </thead>
            <tbody>
            {counter start=($paginator->getRecordSet()->getPageSize() * ($paginator->getCurrentPageNumber() - 1)) print=false}
            {foreach $objects->getItems() as $object}
                <tr>
                    <td><a href="#" class="icon-trash" onclick="if (confirm('Действительно удалить вид нагрузки')) { location.href='workplantermsectionloads.php?action=delete&id={$object->getId()}'; }; return false;"></a></td>
                    <td>{counter}</td>
                    <td><a href="workplantermsectionloads.php?action=edit&id={$object->getId()}" class="icon-pencil"></a></td>
                    <td>{$object->id}</td>
<td>{$object->section_id}</td>
<td>{$object->type_id}</td>
<td>{$object->value}</td>
                </tr>
            {/foreach}
            </tbody>
        </table>

        {CHtml::paginator($paginator, "workplantermsectionloads.php?action=index")}
    {/if}
{/block}

{block name="asu_right"}
    {include file="_corriculum/_workplan/termSectionLoads/common.right.tpl"}
{/block}