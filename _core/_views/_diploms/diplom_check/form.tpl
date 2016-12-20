<form action="antiplagiat.php" method="post" class="form-horizontal">

    {CHtml::hiddenField("action", "save")}
    {CHtml::activeHiddenField("id", $check)}
    {CHtml::activeHiddenField("diplom_id", $check)}

    {CHtml::errorSummary($check)}

	<div class="control-group">
	    {CHtml::activeLabel("check_date_on_antiplagiat", $check)}
	    <div class="controls">
	        {CHtml::activeDateField("check_date_on_antiplagiat", $check)}
	        {CHtml::error("check_date_on_antiplagiat", $check)}
	    </div>
	</div>
	
	<div class="control-group">
	    {CHtml::activeLabel("check_time_on_antiplagiat", $check)}
	    <div class="controls">
	        {CHtml::activeTimeField("check_time_on_antiplagiat", $check)}
	        {CHtml::error("check_time_on_antiplagiat", $check)}
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
	    {CHtml::activeLabel("comments_on_antiplagiat", $check)}
	    <div class="controls">
	        {CHtml::activeTextField("comments_on_antiplagiat", $check)}
	        {CHtml::error("comments_on_antiplagiat", $check)}
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