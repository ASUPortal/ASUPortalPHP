<form action="tickets.php" method="post">
    {CHtml::hiddenField("action", "wizardCompleted")}
    {CHtml::hiddenField("speciality_id", $speciality_id)}
    {CHtml::hiddenField("year_id", $year_id)}
    {CHtml::hiddenField("protocol_id", $protocol_id)}
    {CHtml::hiddenField("signer_id", $signer_id)}

    <p>
        {CHtml::label("Количество билетов", "count")}
        {CHtml::textField("count", 30)}
    </p>

    {for $i = 1 to 5}
        <p>
            {CHtml::label("Дисциплина ", "discipline[]")}
            {CHtml::dropDownList("discipline[]", CSEBQuestionsManager::getDisciplinesBySpecialityList($speciality))}
        </p>
    {/for}

    <p>
    {CHtml::submit("Генерировать")}
    </p>
</form>