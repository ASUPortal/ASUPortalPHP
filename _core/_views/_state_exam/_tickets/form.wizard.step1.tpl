<form action="tickets.php" method="post">
    {CHtml::hiddenField("action", "wizardStep2")}

    <p>
    {CHtml::label("Специальность", "speciality_id")}
    {CHtml::dropDownList("speciality_id", CTaxonomyManager::getSpecialitiesList(), 0)}
    </p>

    <p>
    {CHtml::label("Учебный год", "year_id")}
{CHtml::dropDownList("year_id", CTaxonomyManager::getYearsList())}
    </p>

    <p>
    {CHtml::label("Протокол", "protocol_id")}
    {CHtml::dropDownList("protocol_id", CProtocolManager::getAllDepProtocolsList())}
    </p>

    <p>
    {CHtml::label("Подписант", "signer_id")}
    {CHtml::dropDownList("signer_id", CStaffManager::getPersonsList())}
    </p>

    <p>
    {CHtml::submit("Далее")}
    </p>
</form>