<form action="index.php" method="post">
{CHtml::hiddenField("action", "save")}
{CHtml::activeHiddenField("id", $speciality)}

    <p>{CHtml::errorSummary($speciality)}</p>

    <p>
        {CHtml::activeLabel("name", $speciality)}
        {CHtml::activeTextField("name", $speciality)}
        {CHtml::error("name", $speciality)}
    </p>

    <p>
        {CHtml::activeLabel("specialization_id", $speciality)}
        {CHtml::activeDropDownList("specialization_id", $speciality, CTaxonomyManager::getTaxonomy("education_specializations")->getTermsList())}
        {CHtml::error("specialization_id", $speciality)}
    </p>

    <p>
        {CHtml::activeLabel("practice_internship", $speciality)}
        {CHtml::activeTextField("practice_internship", $speciality)}
        {CHtml::error("practice_internship", $speciality)}
    </p>

    <p>
        {CHtml::activeLabel("practice_undergraduate", $speciality)}
        {CHtml::activeTextField("practice_undergraduate", $speciality)}
        {CHtml::error("practice_undergraduate", $speciality)}
    </p>

    <p>
        {CHtml::activeLabel("diplom_preparation", $speciality)}
        {CHtml::activeTextField("diplom_preparation", $speciality)}
        {CHtml::error("diplom_preparation", $speciality)}
    </p>

    <p>
        {CHtml::activeLabel("comment", $speciality)}
        {CHtml::activeTextBox("comment", $speciality)}
        {CHtml::error("comment", $speciality)}
    </p>

    <p>
        {CHtml::submit("Сохранить")}
    </p>
</form>