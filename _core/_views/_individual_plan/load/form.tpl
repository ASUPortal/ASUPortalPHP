<form action="load.php" method="post" enctype="multipart/form-data" class="form-horizontal">
    {CHtml::hiddenField("action", "save")}
    {CHtml::activeHiddenField("id", $load)}
    {CHtml::activeHiddenField("person_id", $load)}

    {CHtml::errorSummary($load)}

    <div class="control-group">
        {CHtml::activeLabel("year_id", $load)}
        <div class="controls">
            {CHtml::activeDropDownList("year_id", $load, CTaxonomyManager::getYearsList())}
            {CHtml::error("year_id", $load)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("type", $load)}
        <div class="controls">
            {CHtml::activeLookup("type", $load, "type_teaching_load", false, array(), true)}
            {CHtml::error("type", $load)}
        </div>
    </div>
    
    <div class="control-group">
	    {CHtml::activeLabel("orders", $load)}
	    <div class="controls">
	        {CHtml::activeLookup("orders", $load, "class.CSearchCatalogOrdersIndPlan", true, ["person_id" => $load->person->getId(), "year_id" => $year])}
	        {CHtml::error("orders", $load)}
	    </div>
	</div>
	
    <div class="control-group">
        {CHtml::activeLabel("conclusion", $load)}
        <div class="controls">
            {CHtml::activeTextBox("conclusion", $load)}
            {CHtml::error("conclusion", $load)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("separate_contract", $load)}
        <div class="controls">
            {CHtml::activeCheckBox("separate_contract", $load)}
            {CHtml::error("separate_contract", $load)}
        </div>
    </div>
    
    {if ($accessLevel)}
	    <div class="control-group">
	        {CHtml::activeLabel("_edit_restriction", $load)}
	        <div class="controls">
	            {CHtml::activeCheckBox("_edit_restriction", $load)}
	            {CHtml::error("_edit_restriction", $load)}
	        </div>
	    </div> 
    {/if}

    <div class="control-group">
        <div class="controls">
            {CHtml::submit("Сохранить")}
        </div>
    </div>
</form>