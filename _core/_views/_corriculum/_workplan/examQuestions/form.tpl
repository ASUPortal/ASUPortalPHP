<form action="workplanexamquestions.php" method="post" enctype="multipart/form-data" class="form-horizontal">
    {CHtml::hiddenField("action", "save")}
    {CHtml::activeHiddenField("id", $object)}
    {CHtml::activeHiddenField("discipline_id", $object)}
    {CHtml::activeHiddenField("year_id", $object)}
    {CHtml::activeHiddenField("plan_id", $object)}
    {CHtml::activeHiddenField("type", $object)}

    {CHtml::errorSummary($object)}
    
        <div class="control-group">
        {CHtml::activeLabel("speciality_id", $object)}
        <div class="controls">
            {CHtml::activeDropDownList("speciality_id", $object, CTaxonomyManager::getSpecialitiesList())}
            {CHtml::error("speciality_id", $object)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("course", $object)}
        <div class="controls">
            {CHtml::activeDropDownList("course", $object, $cources)}
            {CHtml::error("course", $object)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("year_id", $object)}
        <div class="controls">
            {CHtml::activeDropDownList("year_id", $object, CTaxonomyManager::getYearsList())}
            {CHtml::error("year_id", $object)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("category_id", $object)}
        <div class="controls">
            {CHtml::activeDropDownList("category_id", $object, CTaxonomyManager::getTaxonomy("questions_types")->getTermsList())}
            {CHtml::error("category_id", $object)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("discipline_id", $object)}
        <div class="controls">
            {CHtml::activeDropDownList("discipline_id", $object, CTaxonomyManager::getDisciplinesList())}
            {CHtml::error("discipline_id", $object)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("text", $object)}
        <div class="controls">
            {CHtml::activeTextBox("text", $object)}
            {CHtml::error("text", $object)}
        </div>
    </div>
	
    <div class="control-group">
        <div class="controls">
            {CHtml::submit("Сохранить", false)}
        </div>
    </div>
</form>