<table class="table table-striped table-bordered table-hover table-condensed">
    <tr>
        <th></th>
        <th>#</th>
        <th>Номер и дата приказа</th>
        <th>Год</th>
        <th>Тип</th>
    </tr>
    {counter start=0 print=false}
    {foreach $form->person->ordersSAB->getItems() as $order}
        <tr>
            <td><a class="icon-trash" href="#" onclick="if (confirm('Действительно удалить приказ {if !is_null($order->order)}{$order->order->getName()}{/if}?')) { location.href='orderssab.php?action=delete&id={$order->getId()}'; }; return false;"></a></td>
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