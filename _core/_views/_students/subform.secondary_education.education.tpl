<div class="control-group">
{CHtml::activeLabel("year_university_start", $student)}
    <div class="controls">
                        {CHtml::activeTextField("year_university_start", $student, "year_university_start")}
                        {CHtml::error("year_university_start", $student)}
    </div> </div>

<div class="control-group">
{CHtml::activeLabel("year_university_end", $student)}
    <div class="controls">
                        {CHtml::activeTextField("year_university_end", $student, "year_university_end")}
                        {CHtml::error("year_university_end", $student)}
    </div> </div>

<div class="control-group">
{CHtml::activeLabel("education_form_start", $student)}
    <div class="controls">
                        {CHtml::activeDropDownList("education_form_start", $student, CTaxonomyManager::getCacheEducationForms()->getItems())}
                        {CHtml::error("education_form_start", $student)}
    </div> </div>

<div class="control-group">
{CHtml::activeLabel("education_form_end", $student)}
    <div class="controls">
                        {CHtml::activeDropDownList("education_form_end", $student, CTaxonomyManager::getCacheEducationForms()->getItems())}
                        {CHtml::error("education_form_end", $student)}
    </div> </div>