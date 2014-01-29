<div class="control-group">
    {CHtml::activeLabel("year_school_end", $student)}
    <div class="controls">
    {CHtml::activeTextField("year_school_end", $student, "year_school_end")}
    {CHtml::error("year_school_end", $student)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("primary_education_type_id", $student)}
    <div class="controls">
    {CHtml::activeDropDownList("primary_education_type_id", $student, CTaxonomyManager::getTaxonomy("primary_education")->getTermsList())}
    {CHtml::error("primary_education_type_id", $student)}
    </div>
</div>