<p>
    {CHtml::activeLabel("fio", $student)}
    {CHtml::activeTextField("fio", $student)}
    {CHtml::error("fio", $student)}
</p>

<p>
    {CHtml::activeLabel("gender_id", $student)}
    {CHtml::activeDropDownList("gender_id", $student, CTaxonomyManager::getGendersList())}
    {CHtml::error("gender_id", $student)}
</p>

<p>
    {CHtml::activeLabel("group_id", $student)}
    {CHtml::activeDropDownList("group_id", $student, $groups)}
    {CHtml::error("group_id", $student)}
</p>

<p>
    {CHtml::activeLabel("telephone", $student)}
    {CHtml::activeTextField("telephone", $student)}
    {CHtml::error("telephone", $student)}
</p>

<p>
    {CHtml::activeLabel("bud_contract", $student)}
    {CHtml::activeDropDownList("bud_contract", $student, $forms)}
    {CHtml::error("bud_contract", $student)}
</p>

<p>
    {CHtml::activeLabel("stud_num", $student)}
    {CHtml::activeTextField("stud_num", $student)}
    {CHtml::error("stud_num", $student)}
</p>