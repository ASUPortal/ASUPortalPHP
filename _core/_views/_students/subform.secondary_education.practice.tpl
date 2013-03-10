<p>
{CHtml::activeLabel("practice_internship_mark_id", $student)}
                        {CHtml::activeDropDownList("practice_internship_mark_id", $student, CTaxonomyManager::getMarksList())}
                        {CHtml::error("practice_internship_mark_id", $student)}
</p>

<p>
{CHtml::activeLabel("practice_undergraduate_mark_id", $student)}
                        {CHtml::activeDropDownList("practice_undergraduate_mark_id", $student, CTaxonomyManager::getMarksList())}
                        {CHtml::error("practice_undergraduate_mark_id", $student)}
</p>

<p>
{CHtml::activeLabel("exam_complex_mark_id", $student)}
                        {CHtml::activeDropDownList("exam_complex_mark_id", $student, CTaxonomyManager::getMarksList())}
                        {CHtml::error("exam_complex_mark_id", $student)}
</p>