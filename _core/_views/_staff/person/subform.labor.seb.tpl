<table border="1" cellpadding="2" cellspacing="0">
    <tr>
        <th></th>
        <th>#</th>
        <th>Номер и дата приказа</th>
        <th>Год</th>
        <th>Тип</th>
    </tr>
    {foreach $form->person->ordersSAB->getItems() as $order}
        <tr>
            <td><a href="#" onclick="if (confirm('Действительно удалить приказ {if !is_null($order->order)}{$order->order->getName()}{/if}?')) { location.href='orderssab.php?action=delete&id={$order->getId()}'; }; return false;"><img src="{$web_root}images/todelete.png"></a></td>
            <td>{counter}</td>
            <td><a href="orderssab.php?action=edit&id={$order->getId()}">
                {if !is_null($order->order)}
                    {$order->order->getName()}
                {/if}
            </a></td>
            <td>
                {if !is_null($order->year)}
                    {$order->year->getValue()}
                {/if}
            </td>
            <td>
                {if !is_null($order->type)}
                    {$order->type->getValue()}
                {/if}
            </td>
        </tr>
    {/foreach}
</table>