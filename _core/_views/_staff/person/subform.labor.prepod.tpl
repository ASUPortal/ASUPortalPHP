<p>Общая ставка: {$form->person->getOrdersRate()}</p>

<ul class="nav nav-tabs">
    <li class="active"><a data-toggle="tab" href="#tabs-1">Основной</a></li>
    <li><a data-toggle="tab" href="#tabs-2">Совместительство</a></li>
    <li><a data-toggle="tab" href="#tabs-3">Дополнительно</a></li>
</ul>
<div class="tab-content">
    <div id="tabs-1" class="tab-pane active">
        <p><strong>Бюджет</strong></p>
        <table class="table table-striped table-bordered table-hover table-condensed">
            <tr>
                <th>#</th>
                <th>Приказ</th>
                <th>Срок действия</th>
            </tr>
            {counter start=0 print=false}
            {foreach $form->person->getActiveOrdersByType(2, 2)->getItems() as $order}
                <tr>
                    <td>{counter}</td>
                    <td><a href="{$web_root}_modules/_orders/index.php?action=viewOrder&id={$order->getId()}">Приказ № {$order->num_order} от {$order->date_order}</a></td>
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
            {counter start=0 print=false}
            {foreach $form->person->getActiveOrdersByType(3, 2)->getItems() as $order}
                <tr>
                    <td>{counter}</td>
                    <td><a href="{$web_root}_modules/_orders/index.php?action=viewOrder&id={$order->getId()}">Приказ № {$order->num_order} от {$order->date_order}</a></td>
                    <td>С {$order->date_begin} по {$order->date_end}</td>
                </tr>
            {/foreach}
        </table>
    </div>
    <div id="tabs-2" class="tab-pane">
        <p><strong>Бюджет</strong></p>
        <table class="table table-striped table-bordered table-hover table-condensed">
            <tr>
                <th>#</th>
                <th>Приказ</th>
                <th>Срок действия</th>
            </tr>
            {counter start=0 print=false}
            {foreach $form->person->getActiveOrdersByType(2, 3)->getItems() as $order}
                <tr>
                    <td>{counter}</td>
                    <td><a href="{$web_root}_modules/_orders/index.php?action=viewOrder&id={$order->getId()}">Приказ № {$order->num_order} от {$order->date_order}</a></td>
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
            {counter start=0 print=false}
            {foreach $form->person->getActiveOrdersByType(3, 3)->getItems() as $order}
                <tr>
                    <td>{counter}</td>
                    <td><a href="{$web_root}_modules/_orders/index.php?action=viewOrder&id={$order->getId()}">Приказ № {$order->num_order} от {$order->date_order}</a></td>
                    <td>С {$order->date_begin} по {$order->date_end}</td>
                </tr>
            {/foreach}
        </table>
    </div>
    <div id="tabs-3" class="tab-pane">
        <p><strong>Бюджет</strong></p>
        <table class="table table-striped table-bordered table-hover table-condensed">
            <tr>
                <th>#</th>
                <th>Приказ</th>
                <th>Срок действия</th>
            </tr>
            {counter start=0 print=false}
            {foreach $form->person->getActiveOrdersByType(2, 4)->getItems() as $order}
                <tr>
                    <td>{counter}</td>
                    <td><a href="{$web_root}_modules/_orders/index.php?action=viewOrder&id={$order->getId()}">Приказ № {$order->num_order} от {$order->date_order}</a></td>
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
            {counter start=0 print=false}
            {foreach $form->person->getActiveOrdersByType(3, 4)->getItems() as $order}
                <tr>
                    <td>{counter}</td>
                    <td><a href="{$web_root}_modules/_orders/index.php?action=viewOrder&id={$order->getId()}">Приказ № {$order->num_order} от {$order->date_order}</a></td>
                    <td>С {$order->date_begin} по {$order->date_end}</td>
                </tr>
            {/foreach}
        </table>
    </div>
</div>