{extends file="_core.3col.tpl"}

{block name="asu_center"}
<h2>Приказы</h2>
{CHtml::helpForCurrentPage()}

<script>
    jQuery(document).ready(function(){
        jQuery("#rated").change(function() {
            if (jQuery("#rated").is(":checked")) {
                window.location.href = "?rated=1";
            } else {
                window.location.href = "?rated=0";
            }
        });
    });
</script>

{CHtml::checkBox("rated", 1, $rated, "rated")} - с приказами

    <table class="table table-striped table-bordered table-hover table-condensed">
    <tr>
        <th rowspan="2">#</th>
        <th rowspan="2">Сотрудник</th>
        <th rowspan="2">Общая ставка</th>
        <th colspan="3">Введенные приказы</th>
    </tr>
    <tr>
        <th>Основной</th>
        <th>Совместительство</th>
        <th>Дополнительно</th>
    </tr>

    {counter start=($paginator->getRecordSet()->getPageSize() * ($paginator->getCurrentPageNumber() - 1)) print=false}
    {foreach $persons->getItems() as $person}
    <tr>
        <td>{counter}</td>
        <td><a href="?action=view&id={$person->getId()}">{$person->getName()}</a></td>
        <td>{$person->getOrdersRate()}</td>
        <td>
            <ul>
                <li>Бюджет - {$person->getActiveOrdersByType(2, 2)->getCount()}</li>
                <li>Внебюджет - {$person->getActiveOrdersByType(3, 2)->getCount()}</li>
            </ul>
        </td>
        <td>
            <ul>
                <li>Бюджет - {$person->getActiveOrdersByType(2, 3)->getCount()}</li>
                <li>Внебюджет - {$person->getActiveOrdersByType(3, 3)->getCount()}</li>
            </ul>
        </td>
        <td>
            <ul>
                <li>Бюджет - {$person->getActiveOrdersByType(2, 4)->getCount()}</li>
                <li>Внебюджет - {$person->getActiveOrdersByType(3, 4)->getCount()}</li>
            </ul>
        </td>
    </tr>
    {/foreach}
</table>
{CHtml::paginator($paginator, "?action=index&rated=$rated")}
{/block}

{block name="asu_right"}
{include file="_orders/index.right.tpl"}
{/block}