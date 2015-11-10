{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Образовательные технологии</h2>

    {if ($objects->getCount() == 0)}
        Нет объектов для отображения
    {else}
        <table class="table table-striped table-bordered table-hover table-condensed">
            <thead>
                <tr>
                    <th width="16">&nbsp;</th>
                    <th width="16">#</th>
                    <th width="16">&nbsp;</th>
                    <th>{CHtml::tableOrder("technology_id", $objects->getFirstItem())}</th>
                    <th>{CHtml::tableOrder("value", $objects->getFirstItem())}</th>
                </tr>
            </thead>
            <tbody>
            {counter start=($paginator->getRecordSet()->getPageSize() * ($paginator->getCurrentPageNumber() - 1)) print=false}
            {foreach $objects->getItems() as $object}
                <tr>
                    <td><a href="#" class="icon-trash" onclick="if (confirm('Действительно удалить образовательные технологии')) { location.href='workplancontenttechnologies.php?action=delete&id={$object->getId()}'; }; return false;"></a></td>
                    <td>{$object->ordering}</td>
                    <td><a href="workplancontenttechnologies.php?action=edit&id={$object->getId()}" class="icon-pencil"></a></td>
                    <td>{$object->technology}</td>
                    <td>{$object->value}</td>
                </tr>
            {/foreach}
            </tbody>
        </table>

        {CHtml::paginator($paginator, "workplancontenttechnologies.php?action=index")}
    {/if}
{/block}

{block name="asu_right"}
    {include file="_corriculum/_workplan/contentTechnology/common.right.tpl"}
{/block}