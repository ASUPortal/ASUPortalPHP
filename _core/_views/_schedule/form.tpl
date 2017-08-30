<form action="index.php" method="post" class="form-horizontal">
    {CHtml::hiddenField("action", "save")}
    {CHtml::hiddenField("redirect", CRequest::getString("redirect"))}
    {CHtml::hiddenField("nameId", CRequest::getInt("nameId"))}
    {CHtml::activeHiddenField("id", $schedule)}
    
    {CHtml::errorSummary($schedule)}
    
    <div class="control-group">
        {CHtml::activeLabel("user_id", $schedule)}
        <div class="controls">
            {CHtml::activeDropDownList("user_id", $schedule, $lecturers)}
            {CHtml::error("user_id", $schedule)}
        </div>
    </div>
    
    <div class="control-group">
        {CHtml::activeLabel("year", $schedule)}
        <div class="controls">
            {CHtml::activeDropDownList("year", $schedule, CTaxonomyManager::getYearsList())}
            {CHtml::error("year", $schedule)}
        </div>
    </div>
    
    <div class="control-group">
	    {CHtml::activeLabel("month", $schedule)}
	    <div class="controls">
	        {CHtml::activeDropDownList("month", $schedule, CTaxonomyManager::getYearPartsList())}
	        {CHtml::error("month", $schedule)}
	    </div>
	</div>
	
    <div class="control-group">
        {CHtml::activeLabel("day", $schedule)}
        <div class="controls">
            {CHtml::activeDropDownList("day", $schedule, $days, "", "", "", "", true)}
            {CHtml::error("day", $schedule)}
        </div>
    </div>
    	
    <div class="control-group">
        {CHtml::activeLabel("number", $schedule)}
        <div class="controls">
            {CHtml::activeDropDownList("number", $schedule, $times, "", "", "", "", true)}
            {CHtml::error("number", $schedule)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("kind", $schedule)}
        <div class="controls">
            {CHtml::activeDropDownList("kind", $schedule, $kindWorks, "", "", "", "", true)}
            {CHtml::error("kind", $schedule)}
        </div>
    </div>
	
	<div class="control-group">
	    {CHtml::activeLabel("length", $schedule)}
	    <div class="controls">
	        {CHtml::activeTextField("length", $schedule)}
	        {CHtml::error("length", $schedule)}
	    </div>
	</div>
	
    <div class="control-group">
        {CHtml::activeLabel("study", $schedule)}
        <div class="controls">
            {CHtml::activeDropDownList("study", $schedule, CTaxonomyManager::getDisciplinesList())}
            {CHtml::error("study", $schedule)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("grup", $schedule)}
        <div class="controls">
            {CHtml::activeDropDownList("grup", $schedule, $groups)}
            {CHtml::error("grup", $schedule)}
        </div>
    </div>

	<div class="control-group">
	    {CHtml::activeLabel("place", $schedule)}
	    <div class="controls">
	        {CHtml::activeTextField("place", $schedule)}
	        {CHtml::error("place", $schedule)}
	    </div>
	</div>
				
    <div class="control-group">
        <div class="controls">
            {CHtml::submit("Сохранить")}
        </div>
    </div>
</form>