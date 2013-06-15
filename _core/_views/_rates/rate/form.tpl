<form action="index.php" method="post">
    {CHtml::hiddenField("action", "save")}
    {CHtml::activeHiddenField("id", $rate)}

    <p>
        {CHtml::activeLabel("title", $rate)}
        {CHtml::activeTextField("title", $rate)}
        {CHtml::error("title", $rate)}
    </p>

    <p>
        {CHtml::activeLabel("alias", $rate)}
        {CHtml::activeTextField("alias", $rate)}
        {CHtml::error("alias", $rate)}
    </p>

    <p>
        {CHtml::activeLabel("value", $rate)}
        {CHtml::activeTextField("value", $rate)}
        {CHtml::error("value", $rate)}
    </p>

    <p>
        {CHtml::activeLabel("year_id", $rate)}
        {CHtml::activeDropDownList("year_id", $rate, CTaxonomyManager::getYearsList())}
        {CHtml::error("year_id", $rate)}
    </p>

    <p>
        {CHtml::activeLabel("category_id", $rate)}
        {CHtml::activeDropDownList("category_id", $rate, CTaxonomyManager::getTaxonomy("rates_category")->getTermsList())}
        {CHtml::error("category_id", $rate)}
    </p>

    <p>
        {CHtml::submit("Сохранить")}
    </p>
</form>