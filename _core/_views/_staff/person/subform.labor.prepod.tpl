<p>Общая ставка: {$form->person->getOrdersRate()}</p>

<div id="tabs-orders-education">
    <ul style="height: 32px; ">
        <li><a href="#tabs-1">Основной</a></li>
        <li><a href="#tabs-2">Совместительство</a></li>
        <li><a href="#tabs-3">Дополнительно</a></li>
    </ul>
    <div id="tabs-1">
        <p><strong>Бюджет</strong></p>
        <table class="table table-striped table-bordered table-hover table-condensed">
            <tr>
                <th>#</th>
                <th>Приказ</th>
                <th>Срок действия</th>
            </tr>
            {foreach $form->person->getActiveOrdersByType(2, 2)->getItems() as $order}
                <tr>
                    <td>{counter}</td>
                    <td><a href="{$web_root}_modules/_orders/?action=viewOrder&id={$order->getId()}">Приказ № {$order->num_order} от {$order->date_order}</a></td>
                    <td>С {$order->date_begin} по {$order->date_end}</td>
                </tr>
            {/foreach}
        </table>

        <p><strong>Внебюджет</strong></p>
        <table class="table table-striped table-bordered table-hover table-condensed">
            <tr>
                <th>#</th>
                <th>Приказ</th>
                <th>Срок действия</th>
            </tr>
            {foreach $form->person->getActiveOrdersByType(3, 2)->getItems() as $order}
                <tr>
                    <td>{counter}</td>
                    <td><a href="{$web_root}_modules/_orders/?action=viewOrder&id={$order->getId()}">Приказ № {$order->num_order} от {$order->date_order}</a></td>
                    <td>С {$order->date_begin} по {$order->date_end}</td>
                </tr>
            {/foreach}
        </table>
    </div>
    <div id="tabs-2">
        <p><strong>Бюджет</strong></p>
        <table class="table table-striped table-bordered table-hover table-condensed">
            <tr>
                <th>#</th>
                <th>Приказ</th>
                <th>Срок действия</th>
            </tr>
            {foreach $form->person->getActiveOrdersByType(2, 3)->getItems() as $order}
                <tr>
                    <td>{counter}</td>
                    <td><a href="{$web_root}_modules/_orders/?action=viewOrder&id={$order->getId()}">Приказ № {$order->num_order} от {$order->date_order}</a></td>
                    <td>С {$order->date_begin} по {$order->date_end}</td>
                </tr>
            {/foreach}
        </table>

        <p><strong>Внебюджет</strong></p>
        <table class="table table-striped table-bordered table-hover table-condensed">
            <tr>
                <th>#</th>
                <th>Приказ</th>
                <th>Срок действия</th>
            </tr>
            {foreach $form->person->getActiveOrdersByType(3, 3)->getItems() as $order}
                <tr>
                    <td>{counter}</td>
                    <td><a href="{$web_root}_modules/_orders/?action=viewOrder&id={$order->getId()}">Приказ № {$order->num_order} от {$order->date_order}</a></td>
                    <td>С {$order->date_begin} по {$order->date_end}</td>
                </tr>
            {/foreach}
        </table>
    </div>
    <div id="tabs-3">
        <p><strong>Бюджет</strong></p>
        <table class="table table-striped table-bordered table-hover table-condensed">
            <tr>
                <th>#</th>
                <th>Приказ</th>
                <th>Срок действия</th>
            </tr>
            {foreach $form->person->getActiveOrdersByType(2, 4)->getItems() as $order}
                <tr>
                    <td>{counter}</td>
                    <td><a href="{$web_root}_modules/_orders/?action=viewOrder&id={$order->getId()}">Приказ № {$order->num_order} от {$order->date_order}</a></td>
                    <td>С {$order->date_begin} по {$order->date_end}</td>
                </tr>
            {/foreach}
        </table>

        <p><strong>Внебюджет</strong></p>
        <table class="table table-striped table-bordered table-hover table-condensed">
            <tr>
                <th>#</th>
                <th>Приказ</th>
                <th>Срок действия</th>
            </tr>
            {foreach $form->person->getActiveOrdersByType(3, 4)->getItems() as $order}
                <tr>
                    <td>{counter}</td>
                    <td><a href="{$web_root}_modules/_orders/?action=viewOrder&id={$order->getId()}">Приказ № {$order->num_order} от {$order->date_order}</a></td>
                    <td>С {$order->date_begin} по {$order->date_end}</td>
                </tr>
            {/foreach}
        </table>
    </div>
</div>