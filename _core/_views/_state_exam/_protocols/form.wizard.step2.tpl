<form action="protocols.php" method="post">
    {CHtml::hiddenField("action", "wizardCompleted")}
    {CHtml::hiddenField("year_id", $year_id)}
    {CHtml::hiddenField("sign_date", $sign_date)}
    {CHtml::hiddenField("group_id", $group_id)}
    {CHtml::hiddenField("chairman_id", $chairman_id)}
    {CHtml::hiddenField("master_id", $year_id)}
    {foreach $members as $m}
        {CHtml::hiddenField("members[]", $m)}
    {/foreach}

    <table border="1" cellpadding="0" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>#</th>
                <th>Студент</th>
                <th>Номер билета</th>
                <th>Оценка</th>
                <th>Вопросы</th>
            </tr>
        </thead>
        {foreach $students as $student}
            <tr>
                <td>{$student->getId()}</td>
                <td>{$student->getName()}</td>
                <td>
                    {CHtml::dropDownList("student[{$student->getId()}][ticket_id]", CSEBTicketsManager::getTicketsByYearAndSpecialityList($year, $speciality))}
                </td>
                <td>
                    {CHtml::dropDownList("student[{$student->getId()}][mark_id]", CTaxonomyManager::getMarksList())}
                </td>
                <td>
                    {CHtml::textBox("student[{$student->getId()}][questions]")}
                </td>
            </tr>
        {/foreach}
    </table>

    <p>
        {CHtml::submit("Далее")}
    </p>
</form>