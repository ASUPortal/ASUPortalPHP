<form action="index.php" method="post" class="form-horizontal">
    {CHtml::hiddenField("action", "save")}
    {CHtml::activeHiddenField("id", $corriculum)}

    <div class="control-group">
        {CHtml::activeLabel("title", $corriculum)}
        <div class="controls">
            {CHtml::activeTextField("title", $corriculum)}
            {CHtml::error("title", $corriculum)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("description", $corriculum)}
        <div class="controls">
            {CHtml::activeTextBox("description", $corriculum)}
            {CHtml::error("description", $corriculum)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("direction_id", $corriculum)}
        <div class="controls">
            {CHtml::activeDropDownList("direction_id", $corriculum, CTaxonomyManager::getSpecialitiesList())}
            {CHtml::error("direction_id", $corriculum)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("basic_education_id", $corriculum)}
        <div class="controls">
            {CHtml::activeDropDownList("basic_education_id", $corriculum, CTaxonomyManager::getTaxonomy("primary_education")->getTermsList())}
            {CHtml::error("basic_education_id", $corriculum)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("profile_id", $corriculum)}
        <div class="controls">
            {CHtml::activeDropDownList("profile_id", $corriculum, CTaxonomyManager::getTaxonomy("education_specializations")->getTermsList())}
            {CHtml::error("profile_id", $corriculum)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("qualification_id", $corriculum)}
        <div class="controls">
            {CHtml::activeDropDownList("qualification_id", $corriculum, CTaxonomyManager::getTaxonomy("corriculum_skill")->getTermsList())}
            {CHtml::error("qualification_id", $corriculum)}
        </div>
    </div>
    <div class="control-group">
        {CHtml::activeLabel("form_id", $corriculum)}
        <div class="controls">
            {CHtml::activeDropDownList("form_id", $corriculum, CTaxonomyManager::getCacheEducationForms()->getItems())}
            {CHtml::error("form_id", $corriculum)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("duration", $corriculum)}
        <div class="controls">
            {CHtml::activeTextField("duration", $corriculum)}
            {CHtml::error("duration", $corriculum)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("final_exam_title", $corriculum)}
        <div class="controls">
            {CHtml::activeTextField("final_exam_title", $corriculum)}
            {CHtml::error("final_exam_title", $corriculum)}
        </div>
    </div>
    
    <div class="control-group">
        {CHtml::activeLabel("load_classroom", $corriculum)}
        <div class="controls">
            {CHtml::activeTextField("load_classroom", $corriculum)}
            {CHtml::error("load_classroom", $corriculum)}
        </div>
    </div>
    
    <div class="control-group">
        {CHtml::activeLabel("load_total", $corriculum)}
        <div class="controls">
            {CHtml::activeTextField("load_total", $corriculum)}
            {CHtml::error("load_total", $corriculum)}
        </div>
    </div>

    <div class="control-group">
        <div class="controls">
        {CHtml::submit("Сохранить")}
        </div>
    </div>
</form>