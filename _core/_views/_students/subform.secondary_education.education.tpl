<p>
{CHtml::activeLabel("year_university_start", $student)}
                        {CHtml::activeTextField("year_university_start", $student, "year_university_start")}
                        {CHtml::error("year_university_start", $student)}
</p>

<p>
{CHtml::activeLabel("year_university_end", $student)}
                        {CHtml::activeTextField("year_university_end", $student, "year_university_end")}
                        {CHtml::error("year_university_end", $student)}
</p>

<p>
{CHtml::activeLabel("education_form_start", $student)}
                        {CHtml::activeDropDownList("education_form_start", $student, CTaxonomyManager::getCacheEducationForms()->getItems())}
                        {CHtml::error("education_form_start", $student)}
</p>

<p>
{CHtml::activeLabel("education_form_end", $student)}
                        {CHtml::activeDropDownList("education_form_end", $student, CTaxonomyManager::getCacheEducationForms()->getItems())}
                        {CHtml::error("education_form_end", $student)}
</p>

<p>
{CHtml::activeLabel("education_specialization_id", $student)}
                        {CHtml::activeDropDownList("education_specialization_id", $student, CTaxonomyManager::getTaxonomy("education_specializations")->getTermsList())}
                        {CHtml::error("education_specialization_id", $student)}
</p>