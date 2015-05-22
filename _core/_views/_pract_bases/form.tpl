<form action="index.php" class="form-horizontal" method="post" enctype="multipart/form-data">
    {CHtml::hiddenField("action", "save")}
    {CHtml::activeHiddenField("id", $practic)}
    
    <div class="control-group">
        {CHtml::activeLabel("name", $practic)}
        <div class="controls">
            {CHtml::activeTextField("name", $practic)}
            {CHtml::error("name", $practic)}
        </div>
    </div>
    <div class="control-group">
        {CHtml::activeLabel("town_id", $practic)}
        <div class="controls">
        	{CHtml::activeLookup("town_id", $practic, "towns")}
            {CHtml::error("town_id", $practic)}
        </div>
    </div>
    <div class="control-group">
        {CHtml::activeLabel("comment", $practic)}
        <div class="controls">
            {CHtml::activeTextField("comment", $practic)}
            {CHtml::error("comment", $practic)}
        </div>
    </div>
    <div class="control-group">
        <div class="controls">
            {CHtml::submit("Сохранить")}
        </div>
    </div>
</form>