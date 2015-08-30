<form action="index.php" class="form-horizontal" method="post" enctype="multipart/form-data">
    {CHtml::hiddenField("action", "save")}
    {CHtml::activeHiddenField("id", $type)}
    
	<div class="control-group">
        {CHtml::activeLabel("name", $type)}
        <div class="controls">
            {CHtml::activeTextField("name", $type)}
            {CHtml::error("name", $type)}
        </div>
    </div>
    
	<div class="control-group">
        {CHtml::activeLabel("weight", $type)}
        <div class="controls">
            {CHtml::activeTextField("weight", $type)}
            {CHtml::error("weight", $type)}
        </div>
    </div>

    <div class="control-group">
        <div class="controls">
            {CHtml::submit("Сохранить")}
        </div>
    </div>
</form>