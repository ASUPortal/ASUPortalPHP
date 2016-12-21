<form action="antiplagiat.php" method="post" class="form-horizontal">

    {CHtml::hiddenField("action", "save")}
    {CHtml::activeHiddenField("id", $check)}
    {CHtml::activeHiddenField("diplom_id", $check)}

    {CHtml::errorSummary($check)}

	<div class="control-group">
	    {CHtml::activeLabel("check_date", $check)}
	    <div class="controls">
	        {CHtml::activeDateField("check_date", $check)}
	        {CHtml::error("check_date", $check)}
	    </div>
	</div>
	
	<div class="control-group">
	    {CHtml::activeLabel("check_time", $check)}
	    <div class="controls">
	        {CHtml::activeTimeField("check_time", $check)}
	        {CHtml::error("check_time", $check)}
	    </div>
	</div>
	
	<div class="control-group">
	    {CHtml::activeLabel("borrowing_percent", $check)}
	    <div class="controls">
	        {CHtml::activeTextField("borrowing_percent", $check)}
	        {CHtml::error("borrowing_percent", $check)}
	    </div>
	</div>
	
	<div class="control-group">
	    {CHtml::activeLabel("citations_percent", $check)}
	    <div class="controls">
	        {CHtml::activeTextField("citations_percent", $check)}
	        {CHtml::error("citations_percent", $check)}
	    </div>
	</div>
	
	<div class="control-group">
	    {CHtml::activeLabel("originality_percent", $check)}
	    <div class="controls">
	        {CHtml::activeTextField("originality_percent", $check)}
	        {CHtml::error("originality_percent", $check)}
	    </div>
	</div>
	
	<div class="control-group">
	    {CHtml::activeLabel("comments", $check)}
	    <div class="controls">
	        {CHtml::activeTextField("comments", $check)}
	        {CHtml::error("comments", $check)}
	    </div>
	</div>
	
	<div class="control-group">
	    {CHtml::activeLabel("responsible_id", $check)}
	    <div class="controls">
	        {CHtml::activeLookup("responsible_id", $check, "staff")}
	        {CHtml::error("responsible_id", $check)}
	    </div>
	</div>

    <div class="control-group">
        <div class="controls">
            {CHtml::submit("Сохранить")}
        </div>
    </div>
</form>