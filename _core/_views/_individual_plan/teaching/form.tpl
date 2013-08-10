{function drawTable type=1}
    <table class="table table-bordered table-hover">
        <tr>
            <th rowspan="3">Название работ</th>
            <th colspan="5">Осенний</th>
            <th colspan="6">Весенний</th>
        </tr>
        <tr>
            <th colspan="5">Фактически выполнено</th>
            <th colspan="6">Фактически выполнено</th>
        </tr>
        <tr>
            <th>сент</th>
            <th>окт</th>
            <th>ноя</th>
            <th>дек</th>
            <th>янв</th>
            <th>февр</th>
            <th>март</th>
            <th>апр</th>
            <th>май</th>
            <th>июнь</th>
            <th>июль</th>
        </tr>
        {foreach $data as $rowId=>$row}
            {if $rowId < count($data)}
            <tr>
                {foreach $row as $cellId=>$cell}
                    {if !in_array($cellId, array(1, 7, 8, 15, 16, 17))}
                        <td>
                            {if in_array($cellId, array(0, 1, 7, 8, 15, 16, 17))}
                                {if $cell != "0"}
                                    {$cell}
                                {/if}
                            {else}
                                {CHtml::textField($object->getFieldName($rowId, $cellId, $type), $cell, "", "no-class", 'style="width: 80%"')}
                            {/if}
                        </td>
                    {/if}
                {/foreach}
            </tr>
            {/if}
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
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="teaching_main">
            {drawTable data=$object->getTableData("main") type=1}
        </div>
        <div class="tab-pane" id="teaching_add">
            {drawTable data=$object->getTableData("add") type=2}
        </div>
    </div>

    <div class="control-group">
        <div class="controls">
            {CHtml::submit("Сохранить")}
        </div>
    </div>
</form>