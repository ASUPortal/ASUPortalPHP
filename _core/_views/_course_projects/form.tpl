<form action="index.php" method="post" class="form-horizontal">
    {CHtml::hiddenField("action", "save")}
    {CHtml::activeHiddenField("id", $courseProject)}

    {CHtml::errorSummary($courseProject)}

    <div class="control-group">
	    {CHtml::activeLabel("group_id", $courseProject)}
	    <div class="controls">
	        {CHtml::activeLookup("group_id", $courseProject, "studentgroup")}
	        {CHtml::error("group_id", $courseProject)}
	    </div>
	</div>
	
	<div class="control-group">
        {CHtml::activeLabel("discipline_id", $courseProject)}
        <div class="controls">
            {CHtml::activeLookup("discipline_id", $courseProject, "subjects")}
            {CHtml::error("discipline_id", $courseProject)}
        </div>
    </div>
    
    <div class="control-group">
        {CHtml::activeLabel("lecturer_id", $courseProject)}
        <div class="controls">
            {CHtml::activeLookup("lecturer_id", $courseProject, "staff")}
            {CHtml::error("lecturer_id", $courseProject)}
        </div>
    </div>
    
    <div class="control-group">
	    {CHtml::activeLabel("order_number", $courseProject)}
	    <div class="controls">
	        {CHtml::activeTextField("order_number", $courseProject)}
	        {CHtml::error("order_number", $courseProject)}
	    </div>
	</div>
	
	<div class="control-group">
	    {CHtml::activeLabel("order_date", $courseProject)}
	    <div class="controls">
	        {CHtml::activeDateField("order_date", $courseProject)}
	        {CHtml::error("order_date", $courseProject)}
	    </div>
	</div>
	
    <div class="control-group">
        <div class="controls">
            {CHtml::submit("Сохранить")}
        </div>
    </div>
</form>