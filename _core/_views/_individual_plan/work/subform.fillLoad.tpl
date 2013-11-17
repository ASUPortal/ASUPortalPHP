<form action="work.php" method="post" class="form-horizontal" id="fillLoadForm">
    {CHtml::hiddenField("load_id", "{$object->getLoad()->getId()}")}
    {CHtml::hiddenField("action", "getDataForAutofill")}

    <div class="control-group">
        {CHtml::label("Основная", "type_1")}
        <div class="controls">
            {CHtml::checkbox("type_1", "1")}
        </div>
    </div>

    <div class="control-group">
        {CHtml::label("Дополнительная", "type_2")}
        <div class="controls">
            {CHtml::checkbox("type_2", "1")}
        </div>
    </div>

    <div class="control-group">
        {CHtml::label("Надбавка", "type_3")}
        <div class="controls">
            {CHtml::checkbox("type_3", "1")}
        </div>
    </div>

    <div class="control-group">
        {CHtml::label("Почасовка", "type_4")}
        <div class="controls">
            {CHtml::checkbox("type_4", "1")}
        </div>
    </div>

    <div class="control-group">
        {CHtml::label("С учетом выезда", "filials")}
        <div class="controls">
            {CHtml::checkbox("filials", "1")}
        </div>
    </div>
</form>