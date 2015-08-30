{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Типы изданий</h2>

    {CHtml::helpForCurrentPage()}

    {if ($types->getCount() == 0)}
        Нет объектов для отображения
    {else}
        <table class="table table-striped table-bordered table-hover table-condensed">
            <tr>
                <th></th>
                <th>#</th>
                <th>{CHtml::tableOrder("name", $types->getFirstItem())}</th>
                <th>{CHtml::tableOrder("weight", $types->getFirstItem())}</th>
            </tr>
            {counter start=($paginator->getRecordSet()->getPageSize() * ($paginator->getCurrentPageNumber() - 1)) print=false}
            {foreach $types->getItems() as $type}
				<tr>
					<td><a class="icon-trash" href="#" onclick="if (confirm('Действительно удалить тип {$type->name}')) { location.href='?action=delete&id={$type->id}'; }; return false;"></a></td>
                    <td>{counter}</td>
                    <td><a href="index.php?action=edit&id={$type->getId()}">{$type->name}</a></td>
                    <td>{$type->weight}</td>
                </tr>
            {/foreach}
        </table>
        {CHtml::paginator($paginator, "?action=index")}
    {/if}
{/block}

{block name="asu_right"}
    {include file="_rating_publications/edit.right.tpl"}
{/block}