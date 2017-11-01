<form action="resources.php" class="form-horizontal" method="post" enctype="multipart/form-data">
    {CHtml::hiddenField("action", "save")}
    {CHtml::activeHiddenField("id", $resource)}
    {CHtml::activeHiddenField("person_id", $resource)}
    
    <div class="control-group">
        {CHtml::activeLabel("resource_id", $resource)}
        <div class="controls">
            {CHtml::activeLookup("resource_id", $resource, "scientific_resources", false, array(), true)}
            {CHtml::error("resource_id", $resource)}
        </div>
    </div>
    
    <div class="control-group">
        {CHtml::activeLabel("author_id", $resource)}
        <div class="controls">
            {CHtml::activeTextField("author_id", $resource)}
            {CHtml::error("author_id", $resource)}
        </div>
    </div>
    
    <div class="control-group">
        <div class="controls">
            {CHtml::submit("Сохранить")}
        </div>
    </div>
</form>