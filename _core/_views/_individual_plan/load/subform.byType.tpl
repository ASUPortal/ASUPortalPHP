{function drawTable}
    <font color="red">Отображение может не соответствовать действительности, все еще в разработке. Режим
    редактирования работает корректно</font>
    <table class="table table-bordered table-hover">
        <tr>
            <th rowspan="4">Название работ</th>
            <th rowspan="3" colspan="2">План на семестр</th>
            <th colspan="12">Осенний</th>
            <th rowspan="3" colspan="2">План на семестр</th>
            <th colspan="14">Весенний</th>
            <th rowspan="3" colspan="2">По плану</th>
            <th rowspan="3" colspan="2">Выполнено</th>
        </tr>
        <tr>
            <th colspan="12">Фактически выполнено</th>
            <th colspan="14">Фактически выполнено</th>
        </tr>
        <tr>
            <th colspan="2">сент</th>
            <th colspan="2">окт</th>
            <th colspan="2">ноя</th>
            <th colspan="2">дек</th>
            <th colspan="2">янв</th>
            <th colspan="2">итого</th>
            <th colspan="2">февр</th>
            <th colspan="2">март</th>
            <th colspan="2">апр</th>
            <th colspan="2">май</th>
            <th colspan="2">июнь</th>
            <th colspan="2">июль</th>
            <th colspan="2">итого</th>
        </tr>
        <tr>
            <th>Б</th>
            <th>К</th>
            <th>Б</th>
            <th>К</th>
            <th>Б</th>
            <th>К</th>
            <th>Б</th>
            <th>К</th>
            <th>Б</th>
            <th>К</th>
            <th>Б</th>
            <th>К</th>
            <th>Б</th>
            <th>К</th>
            <th>Б</th>
            <th>К</th>
            <th>Б</th>
            <th>К</th>
            <th>Б</th>
            <th>К</th>
            <th>Б</th>
            <th>К</th>
            <th>Б</th>
            <th>К</th>
            <th>Б</th>
            <th>К</th>
            <th>Б</th>
            <th>К</th>
            <th>Б</th>
            <th>К</th>
            <th>Б</th>
            <th>К</th>
            <th>Б</th>
            <th>К</th>
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
    <a href="load/teaching.php?action=add&id={$person->getId()}&year={$load->year->getId()}" class="icon-pencil"></a>
{else}
    <ul class="nav nav-pills">
        <li class="active"><a href="#teaching{$load->year->getId()}_main" data-toggle="tab">Основная</a></li>
        <li><a href="#teaching{$load->year->getId()}_add" data-toggle="tab">Дополнительная</a></li>
        <li><a href="#teaching{$load->year->getId()}_hours" data-toggle="tab">Почасовка</a></li>
        <li>
            <p class="navbar-text">
                <a href="load/teaching.php?action=add&id={$person->getId()}&year={$load->year->getId()}" class="icon-pencil"></a>
            </p>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="teaching{$load->year->getId()}_main">
            {drawTable data=$load->getTeachingLoad()->getTableData("main")}
        </div>
        <div class="tab-pane" id="teaching{$load->year->getId()}_add">
            {drawTable data=$load->getTeachingLoad()->getTableData("add")}
        </div>
        <div class="tab-pane" id="teaching{$load->year->getId()}_hours">
            {drawTable data=$load->getTeachingLoad()->getTableData("hours")}
        </div>
    </div>
{/if}