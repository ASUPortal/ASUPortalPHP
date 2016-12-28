<form action="index.php" class="form-horizontal" method="post" enctype="multipart/form-data">
    {CHtml::hiddenField("action", "save")}
    {CHtml::activeHiddenField("id", $rate)}

	<div class="control-group">
        {CHtml::activeLabel("dolgnost_id", $rate)}
        <div class="controls">
        	{CHtml::activeLookup("dolgnost_id", $rate, "class.CSearchCatalogPostRate", false, array())}
            {CHtml::error("dolgnost_id", $rate)}
        </div>
    </div>
    
    <div class="control-group">
        {CHtml::activeLabel("rate", $rate)}
        <div class="controls">
            {CHtml::activeTextField("rate", $rate)}
            {CHtml::error("rate", $rate)}
        </div>
    </div>
    
    <div class="control-group">
        {CHtml::activeLabel("year_id", $rate)}
        <div class="controls">
            {CHtml::activeDropDownList("year_id", $rate, CTaxonomyManager::getYearsList())}
            {CHtml::error("year_id", $rate)}
        </div>
    </div>
    
	<div class="control-group">
        {CHtml::activeLabel("comment", $rate)}
        <div class="controls">
            {CHtml::activeTextField("comment", $rate)}
            {CHtml::error("comment", $rate)}
        </div>
    </div>
    
    <div class="control-group">
        <div class="controls">
            {CHtml::submit("Сохранить")}
        </div>
    </div>
</form>