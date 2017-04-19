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
	    {CHtml::activeLabel("is_member", $member)}
	    <div class="controls">
	        {CHtml::activeCheckBox("is_member", $member)}
	        {CHtml::error("is_member", $member)}
	    </div>
	</div>
	
	<div class="control-group">
	    {CHtml::activeLabel("comment", $member)}
	    <div class="controls">
	        {CHtml::activeTextField("comment", $member)}
	        {CHtml::error("comment", $member)}
	    </div>
	</div>
	
    <div class="control-group">
        <div class="controls">
            {CHtml::submit("Сохранить", false)}
        </div>
    </div>
</form>