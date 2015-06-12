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
<th>{CHtml::tableOrder("term_id", $objects->getFirstItem())}</th>
<th>{CHtml::tableOrder("practice_num", $objects->getFirstItem())}</th>
<th>{CHtml::tableOrder("section_num", $objects->getFirstItem())}</th>
<th>{CHtml::tableOrder("title", $objects->getFirstItem())}</th>
<th>{CHtml::tableOrder("hours", $objects->getFirstItem())}</th>
                </tr>
            </thead>
            <tbody>
            {counter start=($paginator->getRecordSet()->getPageSize() * ($paginator->getCurrentPageNumber() - 1)) print=false}
            {foreach $objects->getItems() as $object}
                <tr>
                    <td><a href="#" class="icon-trash" onclick="if (confirm('Действительно удалить практика')) { location.href='workplantermpractices.php?action=delete&id={$object->getId()}'; }; return false;"></a></td>
                    <td>{counter}</td>
                    <td><a href="workplantermpractices.php?action=edit&id={$object->getId()}" class="icon-pencil"></a></td>
                    <td>{$object->id}</td>
<td>{$object->term_id}</td>
<td>{$object->practice_num}</td>
<td>{$object->section_num}</td>
<td>{$object->title}</td>
<td>{$object->hours}</td>
                </tr>
            {/foreach}
            </tbody>
        </table>

        {CHtml::paginator($paginator, "workplantermpractices.php?action=index")}
    {/if}
{/block}

{block name="asu_right"}
    {include file="_corriculum/_workplan/termPractices/common.right.tpl"}
{/block}