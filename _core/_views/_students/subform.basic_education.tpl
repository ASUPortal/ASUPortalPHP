<p>
    {CHtml::activeLabel("year_school_end", $student)}
    {CHtml::activeTextField("year_school_end", $student, "year_school_end")}
    {CHtml::error("year_school_end", $student)}
</p>

<br>

<p>
    {CHtml::activeLabel("primary_education_type_id", $student)}
    {CHtml::activeDropDownList("primary_education_type_id", $student, CTaxonomyManager::getTaxonomy("primary_education")->getTermsList())}
    {CHtml::error("primary_education_type_id", $student)}
</p>