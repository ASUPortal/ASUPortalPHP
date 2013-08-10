{function drawTable}
    <table class="table table-bordered table-hover">
        <tr>
            <th rowspan="3">Название работ</th>
            <th rowspan="3">План на семестр</th>
            <th colspan="6">Осенний</th>
            <th rowspan="3">План на семестр</th>
            <th colspan="7">Весенний</th>
            <th rowspan="3">По плану</th>
            <th rowspan="3">Выполнено</th>
        </tr>
        <tr>
            <th colspan="6">Фактически выполнено</th>
            <th colspan="7">Фактически выполнено</th>
        </tr>
        <tr>
            <th>сент</th>
            <th>окт</th>
            <th>ноя</th>
            <th>дек</th>
            <th>янв</th>
            <th>итого</th>
            <th>февр</th>
            <th>март</th>
            <th>апр</th>
            <th>май</th>
            <th>июнь</th>
            <th>июль</th>
        </tr>
        {foreach $data as $row}
            <tr>
                {foreach $row as $cell}
                    <td>
                        {if $cell != "0"}
                            {$cell}
                        {else}
                            &nbsp;
                        {/if}
                    </td>
                {/foreach}
            </tr>
        {/foreach}
    </table>
{/function}

{if $load->getTeachingLoad()->getFact()->getCount() == 0}
    Нет распределения по видам нагрузки
{else}
    <ul class="nav nav-pills">
        <li class="active"><a href="#teaching{$load->year->getId()}_main" data-toggle="tab">Основная</a></li>
        <li><a href="#teaching{$load->year->getId()}_add" data-toggle="tab">Дополнительная</a></li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="teaching{$load->year->getId()}_main">
            {drawTable data=$load->getTeachingLoad()->getTableData("main")}
        </div>
        <div class="tab-pane" id="teaching{$load->year->getId()}_add">
            {drawTable data=$load->getTeachingLoad()->getTableData("add")}
        </div>
    </div>
{/if}