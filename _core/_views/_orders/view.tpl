{extends file="_core.3col.tpl"}

{block name="asu_center"}

<h2>Приказы сотрудника {$person->getName()}</h2>

<p>Общая ставка: {$person->getOrdersRate()}</p>

    <ul class="nav nav-tabs">
        <li class="active"><a href="#tabs-1" data-toggle="tab">Основной</a></li>
        <li><a href="#tabs-2" data-toggle="tab">Совместительство</a></li>
        <li><a href="#tabs-3" data-toggle="tab">Дополнительно</a></li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane active" id="tabs-1">
            <p><strong>Бюджет</strong></p>
            <table class="table table-striped table-bordered table-hover table-condensed">
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
            <table class="table table-striped table-bordered table-hover table-condensed">
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
        <div class="tab-pane" id="tabs-2">
            <p><strong>Бюджет</strong></p>
            <table class="table table-striped table-bordered table-hover table-condensed">
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
            <table class="table table-striped table-bordered table-hover table-condensed">
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
        <div class="tab-pane" id="tabs-3">
            <p><strong>Бюджет</strong></p>
            <table class="table table-striped table-bordered table-hover table-condensed">
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
            <table class="table table-striped table-bordered table-hover table-condensed">
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

    <ul class="nav nav-tabs">
        <li class="active"><a href="#tabs-4" data-toggle="tab">Основной</a></li>
        <li><a href="#tabs-5" data-toggle="tab">Совместительство</a></li>
        <li><a href="#tabs-6" data-toggle="tab">Дополнительно</a></li>
    </ul>
    <div class="tab-content">
        <div id="tabs-4" class="tab-pane active">
            <p><strong>Бюджет</strong></p>
            <table class="table table-striped table-bordered table-hover table-condensed">
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
            <table class="table table-striped table-bordered table-hover table-condensed">
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
        <div id="tabs-5" class="tab-pane">
            <p><strong>Бюджет</strong></p>
            <table class="table table-striped table-bordered table-hover table-condensed">
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
            <table class="table table-striped table-bordered table-hover table-condensed">
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
        <div id="tabs-6" class="tab-pane">
            <p><strong>Бюджет</strong></p>
            <table class="table table-striped table-bordered table-hover table-condensed">
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
            <table class="table table-striped table-bordered table-hover table-condensed">
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

<div id="tabs-archive">
    <ul style="height: 32px; ">

    </ul>

</div>
{/block}

{block name="asu_right"}
{include file="_orders/view.right.tpl"}
{/block}