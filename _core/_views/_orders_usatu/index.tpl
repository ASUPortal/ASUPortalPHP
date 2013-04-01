{extends file="_core.3col.tpl"}

{block name="asu_center"}
<h2>Приказы УГАТУ</h2>

    {CHtml::helpForCurrentPage()}

<script>

</script>

<table border="0" width="100%" class="tableBlank">
    <tr>
        <td valign="top">

        </td>
        <td valign="top" width="200px">
            <p>
                <input type="text" id="search" style="width: 100%; " placeholder="Поиск">
            </p>
        </td>
    </tr>
</table>

<table border="1" cellpadding="2" cellspacing="0">
    <tr>
        <th></th>
        <th>#</th>
        <th>{CHtml::tableOrder("orders_type", $orders->getFirstItem())}</th>
        <th>{CHtml::tableOrder("date", $orders->getFirstItem())}</th>
        <th>{CHtml::tableOrder("title", $orders->getFirstItem())}</th>
        <th>Комментарий</th>
    </tr>
    {counter start=(20 * ($paginator->getCurrentPageNumber() - 1)) print=false}
    {foreach $orders->getItems() as $order}
    <tr>
        <td><a href="#" onclick="if (confirm('Действительно удалить приказ {$order->title}')) { location.href='?action=delete&id={$order->id}'; }; return false;"><img src="{$web_root}images/todelete.png"></a></td>
        <td>{counter}</td>
        <td>
            {if !is_null($order->type)}
                {$order->type->getValue()}
            {/if}
        </td>
        <td>
            <a href="?action=edit&id={$order->getId()}">№{$order->num} от {$order->date}</a>
        </td>
        <td>
            <p><b>{$order->title}</b></p>
            <p>{$order->text}</p>
        </td>
        <td>{$order->comment}</td>
    </tr>
    {/foreach}
</table>

    {CHtml::paginator($paginator, "?action=index")}
{/block}

{block name="asu_right"}
{include file="_orders_usatu/index.right.tpl"}
{/block}