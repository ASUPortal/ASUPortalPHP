{if $load->isSeparateContract()}
    <table class="table table-striped table-bordered table-hover table-condensed">
        <tr>
            <th rowspan="4">&nbsp;</th>
            <th rowspan="3" colspan="2">План на семестр</th>
            <th colspan="12">Осенний семестр</th>
            <th rowspan="3" colspan="2">План на семестр</th>
            <th colspan="14">Весенний семестр</th>
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
            <th colspan="2">нояб</th>
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
        {foreach $load->getStudyLoadTable()->getTable(true) as $rows}
            <tr>
                {foreach $rows as $col}
                    <td>{$col}</td>
                {/foreach}
            </tr>
        {/foreach}
    </table>
{else}
<table class="table table-striped table-bordered table-hover table-condensed">
    <tr>
        <th rowspan="3">&nbsp;</th>
        <th rowspan="3">План на семестр</th>
        <th colspan="6">Осенний семестр</th>
        <th rowspan="3">План на семестр</th>
        <th colspan="7">Весенний семестр</th>
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
        <th>нояб</th>
        <th>дек</th>
        <th>янв</th>
        <th>итого</th>
        <th>февр</th>
        <th>март</th>
        <th>апр</th>
        <th>май</th>
        <th>июнь</th>
        <th>июль</th>
        <th>итого</th>
    </tr>
{foreach $load->getStudyLoadTable()->getTable(true) as $rows}
    <tr>
    {foreach $rows as $col}
        <td>{$col}</td>
    {/foreach}
    </tr>
{/foreach}
</table>
{/if}
