{extends file="_core.3col.tpl"}

{block name="asu_center"}

<script>
    jQuery(document).ready(function(){
        jQuery("#tabs").tabs();
        jQuery("#tabs-archive").tabs();
    });
</script>

<h2>Приказы сотрудника {$person->getName()}</h2>

<p>Общая ставка: {$person->getOrdersRate()}</p>

<div id="tabs">
    <ul style="height: 32px; ">
        <li><a href="#tabs-1">Основной</a></li>
        <li><a href="#tabs-2">Совместительство</a></li>
        <li><a href="#tabs-3">Дополнительно</a></li>
    </ul>
    <div id="tabs-1">
        <p><strong>Бюджет</strong></p>
        <table border="1" cellpadding="0" cellspacing="0">
            <tr>
                <th>#</th>
                <th>Приказ</th>
                <th>Срок действия</th>
            </tr>
            {foreach $person->getActiveOrdersByType(2, 2)->getItems() as $order}
            <tr>
                <td>{counter}</td>
                <td><a href="?action=viewOrder&id={$order->getId()}">Приказ № {$order->num_order} от {$order->date_order}</a></td>
                <td>С {$order->date_begin} по {$order->date_end}</td>
            </tr>
            {/foreach}
        </table>

        <p><strong>Внебюджет</strong></p>
        <table border="1" cellpadding="0" cellspacing="0">
            <tr>
                <th>#</th>
                <th>Приказ</th>
                <th>Срок действия</th>
            </tr>
            {foreach $person->getActiveOrdersByType(3, 2)->getItems() as $order}
                <tr>
                    <td>{counter}</td>
                    <td><a href="?action=viewOrder&id={$order->getId()}">Приказ № {$order->num_order} от {$order->date_order}</a></td>
                    <td>С {$order->date_begin} по {$order->date_end}</td>
                </tr>
            {/foreach}
        </table>
    </div>
    <div id="tabs-2">
        <p><strong>Бюджет</strong></p>
        <table border="1" cellpadding="0" cellspacing="0">
            <tr>
                <th>#</th>
                <th>Приказ</th>
                <th>Срок действия</th>
            </tr>
            {foreach $person->getActiveOrdersByType(2, 3)->getItems() as $order}
                <tr>
                    <td>{counter}</td>
                    <td><a href="?action=viewOrder&id={$order->getId()}">Приказ № {$order->num_order} от {$order->date_order}</a></td>
                    <td>С {$order->date_begin} по {$order->date_end}</td>
                </tr>
            {/foreach}
        </table>

        <p><strong>Внебюджет</strong></p>
        <table border="1" cellpadding="0" cellspacing="0">
            <tr>
                <th>#</th>
                <th>Приказ</th>
                <th>Срок действия</th>
            </tr>
            {foreach $person->getActiveOrdersByType(3, 3)->getItems() as $order}
                <tr>
                    <td>{counter}</td>
                    <td><a href="?action=viewOrder&id={$order->getId()}">Приказ № {$order->num_order} от {$order->date_order}</a></td>
                    <td>С {$order->date_begin} по {$order->date_end}</td>
                </tr>
            {/foreach}
        </table>
    </div>
    <div id="tabs-3">
        <p><strong>Бюджет</strong></p>
        <table border="1" cellpadding="0" cellspacing="0">
            <tr>
                <th>#</th>
                <th>Приказ</th>
                <th>Срок действия</th>
            </tr>
            {foreach $person->getActiveOrdersByType(2, 4)->getItems() as $order}
                <tr>
                    <td>{counter}</td>
                    <td><a href="?action=viewOrder&id={$order->getId()}">Приказ № {$order->num_order} от {$order->date_order}</a></td>
                    <td>С {$order->date_begin} по {$order->date_end}</td>
                </tr>
            {/foreach}
        </table>

        <p><strong>Внебюджет</strong></p>
        <table border="1" cellpadding="0" cellspacing="0">
            <tr>
                <th>#</th>
                <th>Приказ</th>
                <th>Срок действия</th>
            </tr>
            {foreach $person->getActiveOrdersByType(3, 4)->getItems() as $order}
                <tr>
                    <td>{counter}</td>
                    <td><a href="?action=viewOrder&id={$order->getId()}">Приказ № {$order->num_order} от {$order->date_order}</a></td>
                    <td>С {$order->date_begin} по {$order->date_end}</td>
                </tr>
            {/foreach}
        </table>
    </div>
</div>

<p><strong>Архив приказов</strong></p>

<div id="tabs-archive">
    <ul style="height: 32px; ">
        <li><a href="#tabs-4">Основной</a></li>
        <li><a href="#tabs-5">Совместительство</a></li>
        <li><a href="#tabs-6">Дополнительно</a></li>
    </ul>
    <div id="tabs-4">
        <p><strong>Бюджет</strong></p>
        <table border="1" cellpadding="0" cellspacing="0">
            <tr>
                <th>#</th>
                <th>Приказ</th>
                <th>Срок действия</th>
            </tr>
            {foreach $person->getArchiveOrdersByType(2, 2)->getItems() as $order}
                <tr>
                    <td>{counter}</td>
                    <td><a href="?action=viewOrder&id={$order->getId()}">Приказ № {$order->num_order} от {$order->date_order}</a></td>
                    <td>С {$order->date_begin} по {$order->date_end}</td>
                </tr>
            {/foreach}
        </table>

        <p><strong>Внебюджет</strong></p>
        <table border="1" cellpadding="0" cellspacing="0">
            <tr>
                <th>#</th>
                <th>Приказ</th>
                <th>Срок действия</th>
            </tr>
            {foreach $person->getArchiveOrdersByType(3, 2)->getItems() as $order}
                <tr>
                    <td>{counter}</td>
                    <td><a href="?action=viewOrder&id={$order->getId()}">Приказ № {$order->num_order} от {$order->date_order}</a></td>
                    <td>С {$order->date_begin} по {$order->date_end}</td>
                </tr>
            {/foreach}
        </table>
    </div>
    <div id="tabs-5">
        <p><strong>Бюджет</strong></p>
        <table border="1" cellpadding="0" cellspacing="0">
            <tr>
                <th>#</th>
                <th>Приказ</th>
                <th>Срок действия</th>
            </tr>
            {foreach $person->getArchiveOrdersByType(2, 3)->getItems() as $order}
                <tr>
                    <td>{counter}</td>
                    <td><a href="?action=viewOrder&id={$order->getId()}">Приказ № {$order->num_order} от {$order->date_order}</a></td>
                    <td>С {$order->date_begin} по {$order->date_end}</td>
                </tr>
            {/foreach}
        </table>

        <p><strong>Внебюджет</strong></p>
        <table border="1" cellpadding="0" cellspacing="0">
            <tr>
                <th>#</th>
                <th>Приказ</th>
                <th>Срок действия</th>
            </tr>
            {foreach $person->getArchiveOrdersByType(3, 3)->getItems() as $order}
                <tr>
                    <td>{counter}</td>
                    <td><a href="?action=viewOrder&id={$order->getId()}">Приказ № {$order->num_order} от {$order->date_order}</a></td>
                    <td>С {$order->date_begin} по {$order->date_end}</td>
                </tr>
            {/foreach}
        </table>
    </div>
    <div id="tabs-6">
        <p><strong>Бюджет</strong></p>
        <table border="1" cellpadding="0" cellspacing="0">
            <tr>
                <th>#</th>
                <th>Приказ</th>
                <th>Срок действия</th>
            </tr>
            {foreach $person->getArchiveOrdersByType(2, 4)->getItems() as $order}
                <tr>
                    <td>{counter}</td>
                    <td><a href="?action=viewOrder&id={$order->getId()}">Приказ № {$order->num_order} от {$order->date_order}</a></td>
                    <td>С {$order->date_begin} по {$order->date_end}</td>
                </tr>
            {/foreach}
        </table>

        <p><strong>Внебюджет</strong></p>
        <table border="1" cellpadding="0" cellspacing="0">
            <tr>
                <th>#</th>
                <th>Приказ</th>
                <th>Срок действия</th>
            </tr>
            {foreach $person->getArchiveOrdersByType(3, 4)->getItems() as $order}
                <tr>
                    <td>{counter}</td>
                    <td><a href="?action=viewOrder&id={$order->getId()}">Приказ № {$order->num_order} от {$order->date_order}</a></td>
                    <td>С {$order->date_begin} по {$order->date_end}</td>
                </tr>
            {/foreach}
        </table>
    </div>
</div>
{/block}

{block name="asu_right"}
{include file="_orders/view.right.tpl"}
{/block}