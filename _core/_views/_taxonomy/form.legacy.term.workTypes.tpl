<form action="index.php" method="post">
    {CHtml::activeHiddenField("id", $term)}
    {CHtml::activeHiddenField("taxonomy_id", $term)}
    {CHtml::hiddenField("action", "saveLegacyTerm")}

	<div class="control-group">
	    {CHtml::activeLabel("name", $term)}
	    <div class="controls">
	        {CHtml::activeTextField("name", $term)}
	        {CHtml::error("name", $term)}
	    </div>
	</div>

	<div class="control-group">
	    {CHtml::activeLabel("name_hours_kind", $term)}
	    <div class="controls">
	        {CHtml::activeTextField("name_hours_kind", $term)}
	        {CHtml::error("name_hours_kind", $term)}
	    </div>
	</div>

	<div class="control-group">
	    {CHtml::activeLabel("is_total", $term)}
	    <div class="controls">
	        {CHtml::activeCheckBox("is_total", $term)}
	        {CHtml::error("is_total", $term)}
	    </div>
	</div>
	
	<div class="control-group">
	    {CHtml::activeLabel("comment", $term)}
	    <div class="controls">
	        {CHtml::activeTextBox("comment", $term)}
	        {CHtml::error("comment", $term)}
	    </div>
	</div>

    <div class="control-group">
        <div class="controls">
            {CHtml::submit("Сохранить")}
        </div>
    </div>
    
</form>
