<form action="disciplines.php" method="post" class="form-horizontal">
	{CHtml::hiddenField("action", "saveStatement")}
	{CHtml::activeHiddenField("id", $statement)}
	{CHtml::activeHiddenField("discipline_id", $statement)}
	
	<div class="control-group">
        {CHtml::activeLabel("author", $statement)}
        <div class="controls">
            {CHtml::activeTextField("author", $statement)}
            {CHtml::error("book_name", $statement)}
        </div>
    </div>
    
    <div class="control-group">
        {CHtml::activeLabel("book_name", $statement)}
        <div class="controls">
            {CHtml::activeTextField("book_name", $statement)}
            {CHtml::error("book_name", $statement)}
        </div>
    </div>
    
    <div class="control-group">
        {CHtml::activeLabel("publishing", $statement)}
        <div class="controls">
            {CHtml::activeTextField("publishing", $statement)}
            {CHtml::error("publishing", $statement)}
        </div>
    </div>
    
    <div class="control-group">
        {CHtml::activeLabel("year_of_publishing", $statement)}
        <div class="controls">
            {CHtml::activeTextField("year_of_publishing", $statement)}
            {CHtml::error("year_of_publishing", $statement)}
        </div>
    </div>
    
    <div class="control-group">
        {CHtml::activeLabel("grif", $statement)}
        <div class="controls">
            {CHtml::activeTextField("grif", $statement)}
            {CHtml::error("grif", $statement)}
        </div>
    </div>
    
    <div class="control-group">
        {CHtml::activeLabel("count_of_copies", $statement)}
        <div class="controls">
            {CHtml::activeTextField("count_of_copies", $statement)}
            {CHtml::error("count_of_copies", $statement)}
        </div>
    </div>
    
    <div class="control-group">
        {CHtml::activeLabel("literature_type", $statement)}
        <div class="controls">
            {CHtml::activeDropDownList("literature_type", $statement, $types)}
            {CHtml::error("literature_type", $statement)}
        </div>
    </div>
    
    <div class="control-group">
    	<div class="controls">
    		{CHtml::submit("Сохранить", false)}
    	</div>
    </div>
	    
</form>