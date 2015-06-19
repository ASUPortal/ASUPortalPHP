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
                    <th>{CHtml::tableOrder("type_id", $objects->getFirstItem())}</th>
                    <th>{CHtml::tableOrder("form_id", $objects->getFirstItem())}</th>
                    <th>{CHtml::tableOrder("funds", $objects->getFirstItem())}</th>
                    <th>{CHtml::tableOrder("places", $objects->getFirstItem())}</th>
                </tr>
            </thead>
            <tbody>
            {counter start=($paginator->getRecordSet()->getPageSize() * ($paginator->getCurrentPageNumber() - 1)) print=false}
            {foreach $objects->getItems() as $object}
                <tr>
                    <td><a href="#" class="icon-trash" onclick="if (confirm('Действительно удалить оценочное средство')) { location.href='workplanmarktypes.php?action=delete&id={$object->getId()}'; }; return false;"></a></td>
                    <td>{counter}</td>
                    <td><a href="workplanmarktypes.php?action=edit&id={$object->getId()}" class="icon-pencil"></a></td>
                    <td>{$object->type}</td>
                    <td>{$object->form}</td>
                    <td>
                        {foreach $object->funds as $fund}
                            <p>{$fund}</p>
                        {/foreach}
                    </td>
                    <td>
                        {foreach $object->places as $place}
                            <p>{$place}</p>
                        {/foreach}
                    </td>
                </tr>
            {/foreach}
            </tbody>
        </table>

        {CHtml::paginator($paginator, "workplanmarktypes.php?action=index")}
    {/if}
{/block}

{block name="asu_right"}
    {include file="_corriculum/_workplan/markTypes/common.right.tpl"}
{/block}