<form action="index.php" method="post" class="form-horizontal">
    {CHtml::hiddenField("action", "save")}
    {CHtml::activeHiddenField("id", $courseProject)}

    {CHtml::errorSummary($courseProject)}

    <div class="control-group">
	    {CHtml::activeLabel("group_id", $courseProject)}
	    <div class="controls">
	        {CHtml::activeDropDownList("group_id", $courseProject, $groups, "", "", "", "", true)}
	        {CHtml::error("group_id", $courseProject)}
	    </div>
	</div>
	
	{if count($disciplines) != 0}
		<div class="control-group">
	        {CHtml::activeLabel("discipline_id", $courseProject)}
	        <div class="controls">
	            {CHtml::activeDropDownList("discipline_id", $courseProject, $disciplines, "", "", "", "", true)}
	            {CHtml::error("discipline_id", $courseProject)}
	        </div>
	    </div>
    {else}
		<div class="control-group">
	        {CHtml::activeLabel("discipline_id", $courseProject)}
	        <div class="controls">
	            {CHtml::activeLookup("discipline_id", $courseProject, "subjects")}
	            {CHtml::error("discipline_id", $courseProject)}
	        </div>
	    </div>
    {/if}
    
    <div class="control-group">
        {CHtml::activeLabel("lecturer_id", $courseProject)}
        <div class="controls">
            {CHtml::activeLookup("lecturer_id", $courseProject, "staff")}
            {CHtml::error("lecturer_id", $courseProject)}
        </div>
    </div>
        
    <div class="control-group">
        {CHtml::activeLabel("chairman_of_commission", $courseProject)}
        <div class="controls">
            {CHtml::activeLookup("chairman_of_commission", $courseProject, "staff")}
            {CHtml::error("chairman_of_commission", $courseProject)}
        </div>
    </div>
    
    <div class="control-group">
	    {CHtml::activeLabel("commision_members", $courseProject)}
	    <div class="controls">
	        {CHtml::activeLookup("commision_members", $courseProject, "staff", true)}
	        {CHtml::error("commision_members", $courseProject)}
	    </div>
	</div>
	
	<div class="control-group">
	    {CHtml::activeLabel("issue_date", $courseProject)}
	    <div class="controls">
	        {CHtml::activeDateField("issue_date", $courseProject)}
	        {CHtml::error("issue_date", $courseProject)}
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
	    {CHtml::activeLabel("main_content", $courseProject)}
	    <div class="controls">
	        {CHtml::activeTextBox("main_content", $courseProject)}
	        {CHtml::error("main_content", $courseProject)}
	    </div>
	</div>
	
	<div class="control-group">
	    {CHtml::activeLabel("requirements_for_registration", $courseProject)}
	    <div class="controls">
	        {CHtml::activeTextBox("requirements_for_registration", $courseProject)}
	        {CHtml::error("requirements_for_registration", $courseProject)}
	    </div>
	</div>
	
	<div class="control-group">
	    {CHtml::activeLabel("graduation_date", $courseProject)}
	    <div class="controls">
	        {CHtml::activeTextField("graduation_date", $courseProject)}
	        {CHtml::error("graduation_date", $courseProject)}
	    </div>
	</div>
	
	<div class="control-group">
	    {CHtml::activeLabel("graduation_time", $courseProject)}
	    <div class="controls">
	        {CHtml::activeTextField("graduation_time", $courseProject)}
	        {CHtml::error("graduation_time", $courseProject)}
	    </div>
	</div>
		
	<div class="control-group">
	    {CHtml::activeLabel("auditorium", $courseProject)}
	    <div class="controls">
	        {CHtml::activeTextField("auditorium", $courseProject)}
	        {CHtml::error("auditorium", $courseProject)}
	    </div>
	</div>
	
	<div class="control-group">
	    {CHtml::activeLabel("protocol_id", $courseProject)}
	    <div class="controls">
	        {CHtml::activeDropDownList("protocol_id", $courseProject, CProtocolManager::getAllDepProtocolsList(), "", "", "", "", true)}
	        {CHtml::error("protocol_id", $courseProject)}
	    </div>
	</div>
	
    <div class="control-group">
        <div class="controls">
            {CHtml::submit("Сохранить")}
        </div>
    </div>
</form>