{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Заголовок страницы списка</h2>

    {if ($objects->getCount() == 0)}
        Нет объектов для отображения
    {else}
        <table class="table table-striped table-bordered table-hover table-condensed">
            <thead>
                <tr>
                    <th width="16">&nbsp;</th>
                    <th width="16">#</th>
                    <th width="16">&nbsp;</th>
                    
                </tr>
            </thead>
            <tbody>
            {counter start=($paginator->getRecordSet()->getPageSize() * ($paginator->getCurrentPageNumber() - 1)) print=false}
            {foreach $objects->getItems() as $object}
                <tr>
                    <td><a href="#" class="icon-trash" onclick="if (confirm('Действительно удалить итоговая аттестация')) { location.href='attestations.php?action=delete&id={$object->getId()}'; }; return false;"></a></td>
                    <td>{counter}</td>
                    <td><a href="attestations.php?action=edit&id={$object->getId()}" class="icon-pencil"></a></td>
                    
                </tr>
            {/foreach}
            </tbody>
        </table>

        {CHtml::paginator($paginator, "attestations.php?action=index")}
    {/if}
{/block}

{block name="asu_right"}
    {include file="_corriculum/_attestations/common.right.tpl"}
{/block}