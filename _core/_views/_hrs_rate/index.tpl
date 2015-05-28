{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Справочник ставок в часах по нагрузке</h2>

    {CHtml::helpForCurrentPage()}

    {if ($rates->getCount() == 0)}
        Нет объектов для отображения
    {else}

        <table class="table table-striped table-bordered table-hover table-condensed">
            <tr>
                <th></th>
                <th>#</th>
                <th>{CHtml::tableOrder("dolgnost_id", $rates->getFirstItem())}</th>
                <th>{CHtml::tableOrder("rate", $rates->getFirstItem())}</th>
                <th>{CHtml::tableOrder("comment", $rates->getFirstItem())}</th>
            </tr>
            {counter start=(20 * ($paginator->getCurrentPageNumber() - 1)) print=false}
            {foreach $rates->getItems() as $rate}
                <tr>
                    <td><a class="icon-trash" href="#" onclick="if (confirm('Действительно удалить ставку {$rate->rate}')) { location.href='?action=delete&id={$rate->id}'; }; return false;"></a></td>
                    <td>{counter}</td>
                    <td><a href="index.php?action=edit&id={$rate->getId()}">{CTaxonomyManager::getPostById($rate->dolgnost_id)->getValue()}</a></td>
                    <td>{$rate->rate}</td>
                    <td>{$rate->comment}</td>
                </tr>
            {/foreach}
        </table>

        {CHtml::paginator($paginator, "?action=index")}
    {/if}
{/block}

{block name="asu_right"}
    {include file="_hrs_rate/index.right.tpl"}
{/block}