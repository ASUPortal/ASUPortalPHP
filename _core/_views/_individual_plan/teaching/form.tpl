{function drawTable type=1}
    <table class="table table-bordered table-hover">
        <tr>
            <th rowspan="4">Название работ</th>
            <th colspan="10">Осенний</th>
            <th colspan="12">Весенний</th>
        </tr>
        <tr>
            <th colspan="10">Фактически выполнено</th>
            <th colspan="12">Фактически выполнено</th>
        </tr>
        <tr>
            <th colspan="2">сент</th>
            <th colspan="2">окт</th>
            <th colspan="2">ноя</th>
            <th colspan="2">дек</th>
            <th colspan="2">янв</th>
            <th colspan="2">февр</th>
            <th colspan="2">март</th>
            <th colspan="2">апр</th>
            <th colspan="2">май</th>
            <th colspan="2">июнь</th>
            <th colspan="2">июль</th>
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
        </tr>
        {foreach $data as $rowId=>$row}
            <tr>
                {foreach $row as $cellId=>$cell}
                    {if !in_array($cellId, array(23, 24))}
                        <td>
                            {if in_array($cellId, array(0))}
                                {$cell}
                            {else}
                                {CHtml::textField($object->getFieldName($rowId, $cellId, $type), $cell, "", "no-class", 'style="width: 40%; padding: 0xp; "')}
                            {/if}
                        </td>
                    {/if}
                {/foreach}
            </tr>
        {/foreach}
    </table>
{/function}

<form action="teaching.php" method="post" enctype="multipart/form-data" class="form-horizontal">
    {CHtml::hiddenField("action", "save")}
    {CHtml::activeHiddenField("kadri_id", $object)}
    {CHtml::activeHiddenField("year_id", $object)}

    {CHtml::errorSummary($object)}

    <ul class="nav nav-pills">
        <li class="active"><a href="#teaching_main" data-toggle="tab">Основная</a></li>
        <li><a href="#teaching_add" data-toggle="tab">Дополнительная</a></li>
        <li><a href="#teaching_hours" data-toggle="tab">Почасовка</a></li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="teaching_main">
            {drawTable data=$object->getTableData("main") type=1}
        </div>
        <div class="tab-pane" id="teaching_add">
            {drawTable data=$object->getTableData("add") type=2}
        </div>
        <div class="tab-pane" id="teaching_hours">
            {drawTable data=$object->getTableData("hours") type=4}
        </div>
    </div>

    <div class="control-group">
        <div class="controls">
            {CHtml::submit("Сохранить")}
        </div>
    </div>
</form>