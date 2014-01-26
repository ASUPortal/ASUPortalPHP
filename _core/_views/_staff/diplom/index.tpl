{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Дипломы сотрудников</h2>

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
<th>{CHtml::tableOrder("obraz_type", $objects->getFirstItem())}</th>
<th>{CHtml::tableOrder("zaved_name", $objects->getFirstItem())}</th>
<th>{CHtml::tableOrder("god_okonch", $objects->getFirstItem())}</th>
<th>{CHtml::tableOrder("spec_name", $objects->getFirstItem())}</th>
<th>{CHtml::tableOrder("comment", $objects->getFirstItem())}</th>
<th>{CHtml::tableOrder("kadri_id", $objects->getFirstItem())}</th>
<th>{CHtml::tableOrder("nomer", $objects->getFirstItem())}</th>
<th>{CHtml::tableOrder("kvalifik", $objects->getFirstItem())}</th>
<th>{CHtml::tableOrder("seriya", $objects->getFirstItem())}</th>
<th>{CHtml::tableOrder("file_attach", $objects->getFirstItem())}</th>
                </tr>
            </thead>
            <tbody>
            {counter start=($paginator->getRecordSet()->getPageSize() * ($paginator->getCurrentPageNumber() - 1)) print=false}
            {foreach $objects->getItems() as $object}
                <tr>
                    <td><a href="#" class="icon-trash" onclick="if (confirm('Действительно удалить диплом сотрудника')) { location.href='diploms.php?action=delete&id={$object->getId()}'; }; return false;"></a></td>
                    <td>{counter}</td>
                    <td><a href="diploms.php?action=edit&id={$object->getId()}" class="icon-pencil"></a></td>
                    <td>{$object->id}</td>
<td>{$object->obraz_type}</td>
<td>{$object->zaved_name}</td>
<td>{$object->god_okonch}</td>
<td>{$object->spec_name}</td>
<td>{$object->comment}</td>
<td>{$object->kadri_id}</td>
<td>{$object->nomer}</td>
<td>{$object->kvalifik}</td>
<td>{$object->seriya}</td>
<td>{$object->file_attach}</td>
                </tr>
            {/foreach}
            </tbody>
        </table>

        {CHtml::paginator($paginator, "diploms.php?action=index")}
    {/if}
{/block}

{block name="asu_right"}
    {include file="_staff/diplom/index.right.tpl"}
{/block}