<form action="orderssab.php" method="post">
    {CHtml::hiddenField("action", "save")}
    {CHtml::activeHiddenField("id", $order)}
    {CHtml::activeHiddenField("person_id", $order)}

    <p>
        {CHtml::activeLabel("order_id", $order)}
        {CHtml::activeDropDownList("order_id", $order, CStaffManager::getUsatuSEBOrdersList())}
        {CHtml::error("order_id", $order)}
    </p>

    <p>
        {CHtml::activeLabel("type_id", $order)}
        {CHtml::activeDropDownList("type_id", $order, CTaxonomyManager::getTaxonomy("order_types_for_sab")->getTermsList())}
        {CHtml::error("type_id", $order)}
    </p>

    <p>
        {CHtml::activeLabel("year_id", $order)}
        {CHtml::activeDropDownList("year_id", $order, CTaxonomyManager::getYearsList())}
        {CHtml::error("year_id", $order)}
    </p>

    <p>
        {CHtml::submit("Сохранить")}
    </p>
</form>