{extends file="_core.component.tpl"}

{block name="asu_center"}
    {if ($objects->getCount() == 0)}
        Нет объектов для отображения
    {else}
        <table class="table table-striped table-bordered table-hover table-condensed">
            <thead>
                <tr>
                    <th rowspan="2" width="16">&nbsp;</th>
                    <th rowspan="2" width="16">#</th>
                    <th rowspan="2" width="16">&nbsp;</th>
                    <th rowspan="2">{CHtml::tableOrder("type_study_activity_id", $objects->getFirstItem())}</th>
                    <th rowspan="2">{CHtml::tableOrder("section_id", $objects->getFirstItem())}</th>
                    <th rowspan="2">{CHtml::tableOrder("control_id", $objects->getFirstItem())}</th>
                    <th rowspan="2">{CHtml::tableOrder("mark", $objects->getFirstItem())}</th>
                    <th rowspan="2">{CHtml::tableOrder("amount_labors", $objects->getFirstItem())}</th>
                    <th colspan="2">Баллы</th>
                </tr>
                <tr>
                    <th>{CHtml::tableOrder("min", $objects->getFirstItem())}</th>
                    <th>{CHtml::tableOrder("max", $objects->getFirstItem())}</th>
            	</tr>
            </thead>
            <tbody>
            {counter start=($paginator->getRecordSet()->getPageSize() * ($paginator->getCurrentPageNumber() - 1)) print=false}
            {foreach $objects->getItems() as $object}
                <tr>
                    <td><a href="#" class="icon-trash" onclick="if (confirm('Действительно удалить вид учебной деятельности')) { location.href='workplantypescontrol.php?action=delete&id={$object->getId()}'; }; return false;"></a></td>
                    <td>{$object->ordering}</td>
                    <td><a href="workplantypescontrol.php?action=edit&id={$object->getId()}" class="icon-pencil"></a></td>
                    <td>{$object->type}</td>
                    <td>{$object->section->name}</td>
                    <td>{$object->control}</td>
                    <td>{$object->mark}</td>
                    <td>{$object->amount_labors}</td>
                    <td>{$object->min}</td>
                    <td>{$object->max}</td>
                </tr>
            {/foreach}
            </tbody>
        </table>

        {CHtml::paginator($paginator, "workplantypescontrol.php?action=index")}
    {/if}
{/block}

{block name="asu_right"}
    {include file="_corriculum/_workplan/typesControl/common.right.tpl"}
{/block}