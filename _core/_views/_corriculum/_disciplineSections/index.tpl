{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Заголовок страницы списка</h2>
    {CHtml::helpForCurrentPage()}

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
<th>{CHtml::tableOrder("corriculum_id", $objects->getFirstItem())}</th>
<th>{CHtml::tableOrder("title", $objects->getFirstItem())}</th>
<th>{CHtml::tableOrder("sectionIndex", $objects->getFirstItem())}</th>
                </tr>
            </thead>
            <tbody>
            {counter start=($paginator->getRecordSet()->getPageSize() * ($paginator->getCurrentPageNumber() - 1)) print=false}
            {foreach $objects->getItems() as $object}
                <tr>
                    <td><a href="#" class="icon-trash" onclick="if (confirm('Действительно удалить семестр')) { location.href='disciplineSections.php?action=delete&id={$object->getId()}'; }; return false;"></a></td>
                    <td>{counter}</td>
                    <td><a href="disciplineSections.php?action=edit&id={$object->getId()}" class="icon-pencil"></a></td>
                    <td>{$object->id}</td>
<td>{$object->corriculum_id}</td>
<td>{$object->title}</td>
<td>{$object->sectionIndex}</td>
                </tr>
            {/foreach}
            </tbody>
        </table>

        {CHtml::paginator($paginator, "disciplineSections.php?action=index")}
    {/if}
{/block}

{block name="asu_right"}
    {include file="_corriculum/_disciplineSections/common.right.tpl"}
{/block}