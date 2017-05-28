<form action="members.php" method="post" class="form-horizontal">

    {CHtml::hiddenField("action", "save")}
    {CHtml::activeHiddenField("id", $member)}
    {CHtml::activeHiddenField("commission_id", $member)}

    {CHtml::errorSummary($member)}
    
	<div class="control-group">
	    {CHtml::activeLabel("person_id", $member)}
	    <div class="controls">
	        {CHtml::activeLookup("person_id", $member, "staff")}
	        {CHtml::error("person_id", $member)}
	    </div>
	</div>
	
	<div class="control-group">
	    {CHtml::activeLabel("date_preview", $member)}
	    <div class="controls">
	        {CHtml::activeTextField("date_preview", $member)}
	        {CHtml::error("date_preview", $member)}
	    </div>
	</div>
	
	<div class="control-group">
	    {CHtml::activeLabel("is_visited", $member)}
	    <div class="controls">
	        {CHtml::activeCheckBox("is_visited", $member)}
	        {CHtml::error("is_visited", $member)}
	    </div>
	</div>
	
	<div class="control-group">
	    {CHtml::activeLabel("not_visited_reason", $member)}
	    <div class="controls">
	        {CHtml::activeTextField("not_visited_reason", $member)}
	        {CHtml::error("not_visited_reason", $member)}
	    </div>
	</div>
	
    <div class="control-group">
        <div class="controls">
            {CHtml::submit("Сохранить", false)}
        </div>
    </div>
</form>