{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Ставки</h2>

    {CHtml::helpForCurrentPage()}

    {if $rates->getCount() == 0}
        Нет данных для отображения
    {else}
        <table border="1" cellpadding="2" cellspacing="0">
            <tr>
                <th></th>
                <th>#</th>
                <th>{CHtml::tableOrder("title", $rates->getFirstItem())}</th>
                <th>{CHtml::tableOrder("alias", $rates->getFirstItem())}</th>
                <th>{CHtml::tableOrder("value", $rates->getFirstItem())}</th>
                <th>{CHtml::tableOrder("category_id", $rates->getFirstItem())}</th>
                <th>{CHtml::tableOrder("year_id", $rates->getFirstItem())}</th>
            </tr>
            {counter start=(20 * ($paginator->getCurrentPageNumber() - 1)) print=false}
            {foreach $rates->getItems() as $rate}
                <tr>
                    <td><a href="#" onclick="if (confirm('Действительно удалить ставку {$rate->title}')) { location.href='?action=delete&id={$rate->getId()}'; }; return false;"><img src="{$web_root}images/todelete.png"></a></td>
                    <td>{counter}</td>
                    <td><a href="?action=edit&id={$rate->getId()}">{$rate->title}</a></td>
                    <td>{$rate->alias}</td>
                    <td>{$rate->value}</td>
                    <td>
                        {if !is_null($rate->category)}
                            {$rate->category->getValue()}
                        {/if}
                    </td>
                    <td>
                        {if !is_null($rate->year)}
                            {$rate->year->getValue()}
                        {/if}
                    </td>
                </tr>
            {/foreach}
        </table>

        {CHtml::paginator($paginator, "?action=index")}
    {/if}
{/block}

{block name="asu_right"}
    {include file="_rates/rate/index.right.tpl"}
{/block}