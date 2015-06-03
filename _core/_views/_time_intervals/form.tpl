<form action="index.php" class="form-horizontal" method="post" enctype="multipart/form-data">
    {CHtml::hiddenField("action", "save")}
    {CHtml::activeHiddenField("id", $year)}
    
	<div class="control-group">
        {CHtml::activeLabel("name", $year)}
        <div class="controls">
            {CHtml::activeTextField("name", $year)}
            {CHtml::error("name", $year)}
        </div>
    </div>
    
	<div class="control-group">
        {CHtml::activeLabel("date_start", $year)}
        <div class="controls">
            {CHtml::activeDateField("date_start", $year)}
            {CHtml::error("date_start", $year)}
        </div>
    </div>
    
    <div class="control-group">
        {CHtml::activeLabel("date_end", $year)}
        <div class="controls">
            {CHtml::activeDateField("date_end", $year)}
            {CHtml::error("date_end", $year)}
        </div>
    </div>
    
    <div class="control-group">
        {CHtml::activeLabel("comment", $year)}
        <div class="controls">
            {CHtml::activeTextField("comment", $year)}
            {CHtml::error("comment", $year)}
        </div>
    </div>

    <div class="control-group">
        <div class="controls">
            {CHtml::submit("Сохранить")}
        </div>
    </div>
</form>