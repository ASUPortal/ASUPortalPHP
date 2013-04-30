<form action="outgoes.php" method="post" enctype="multipart/form-data">
    {CHtml::activeHiddenField("id", $outgo)}
    {CHtml::activeHiddenField("grant_id", $outgo)}
    {CHtml::hiddenField("action", "save")}

    <p>
        {CHtml::activeLabel("category_id", $outgo)}
        {CHtml::activeDropDownList("category_id", $outgo, CTaxonomyManager::getTaxonomy("outgo_categories")->getTermsList())}
        {CHtml::error("category_id", $outgo)}
    </p>

    <p>
        {CHtml::activeLabel("value", $outgo)}
        {CHtml::activeTextField("value", $outgo)}
        {CHtml::error("value", $outgo)}
    </p>

    <p>
        {CHtml::submit("Сохранить")}
    </p>

</form>