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
                    <th>{CHtml::tableOrder("term_id", $objects->getFirstItem())}</th>
                    <th>{CHtml::tableOrder("control_type_id", $objects->getFirstItem())}</th>
                </tr>
            </thead>
            <tbody>
            {counter start=($paginator->getRecordSet()->getPageSize() * ($paginator->getCurrentPageNumber() - 1)) print=false}
            {foreach $objects->getItems() as $object}
                <tr>
                    <td><a href="#" class="icon-trash" onclick="if (confirm('Действительно удалить вид контроля')) { location.href='workplanmediumcontrol.php?action=delete&id={$object->getId()}'; }; return false;"></a></td>
                    <td>{$object->ordering}</td>
                    <td><a href="workplanmediumcontrol.php?action=edit&id={$object->getId()}" class="icon-pencil"></a></td>
                    {if !is_null($object->term->corriculum_discipline_section)}
                    	<td>{$object->term->corriculum_discipline_section->title}</td>
                    {else}
                    	<td><font color="#FF0000">Обновите значение семестра из дисциплины!</font></td>
                    {/if}
                    <td>{$object->controlType}</td>
                </tr>
            {/foreach}
            </tbody>
        </table>
    {/if}
{/block}

{block name="asu_right"}
    {include file="_corriculum/_workplan/mediumControl/common.right.tpl"}
{/block}