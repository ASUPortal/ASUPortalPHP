<form action="questions.php" method="post">
    {CHtml::hiddenField("action", "save")}
    {if isset($question)}
        {CHtml::hiddenField("id", $question->getId())}
    {/if}

    <p>
        {CHtml::label("Дисциплина", "discipline_id")}
        {if isset($question)}
            {CHtml::dropDownList("discipline_id", CTaxonomyManager::getDisciplinesList(), $question->getDiscipline()->getId())}
        {else}
            {CHtml::dropDownList("discipline_id", CTaxonomyManager::getDisciplinesList())}
        {/if}
    </p>

    <p>
        {CHtml::label("Специальность", "speciality_id")}
        {if isset($question)}
            {CHtml::dropDownList("speciality_id", CTaxonomyManager::getSpecialitiesList(), $question->getSpeciality()->getId())}
            {else}
            {CHtml::dropDownList("speciality_id", CTaxonomyManager::getSpecialitiesList())}
        {/if}
    </p>
        
    <p>
        {CHtml::label("Вопрос", "question")}
        {if isset($question)}
            {CHtml::textBox("question", $question->getText())}
        {else}
            {CHtml::textBox("question")}
        {/if}
    </p>

    <p>
        {CHtml::submit("Сохранить")}
    </p>
</form>