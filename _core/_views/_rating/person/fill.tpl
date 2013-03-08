{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Автоматическое заполнение значений</h2>

    <form action="persons.php" method="post">
    {CHtml::hiddenField("action", "fillIndexes")}
    <p>
        {CHtml::activeLabel("persons", $form)}
        {CHtml::activeSelect("persons", $form, CStaffManager::getPersonsList(), true, 5, "CPersonRatingAutofillForm[persons]")}
        {CHtml::personTypeFilter("persons", $form)}
    </p>
        
    <p>
        {CHtml::activeLabel("year_id", $form)}
        {CHtml::activeDropDownList("year_id", $form, CTaxonomyManager::getYearsList())}
    </p>
        
    <p>
        {CHtml::submit("Заполнить")}
    </p>
    </form>
{/block}

{block name="asu_right"}
    {include file="_rating/person/fill.right.tpl"}
{/block}