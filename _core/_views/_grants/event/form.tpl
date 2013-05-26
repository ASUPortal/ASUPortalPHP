<form action="events.php" method="post" enctype="multipart/form-data">
    {CHtml::activeHiddenField("id", $event)}
    {CHtml::activeHiddenField("grant_id", $event)}
    {CHtml::hiddenField("action", "save")}

    <p>
        {CHtml::activeLabel("title", $event)}
        {CHtml::activeTextField("title", $event)}
        {CHtml::error("title", $event)}
    </p>

    <p>
        {CHtml::activeLabel("date_start", $event)}
        {CHtml::activeDateField("date_start", $event, "date_start")}
        {CHtml::error("date_start", $event)}
    </p>

    <p>
        {CHtml::activeLabel("date_end", $event)}
        {CHtml::activeDateField("date_end", $event, "date_end")}
        {CHtml::error("date_end", $event)}
    </p>

    <p>
        {CHtml::activeLabel("type_id", $event)}
        {CHtml::activeDropDownList("type_id", $event, CTaxonomyManager::getTaxonomy("event_type")->getTermsList())}
        {CHtml::error("type_id", $event)}
    </p>

    <p>
        {CHtml::activeLabel("address", $event)}
        {CHtml::activeTextBox("address", $event)}
        {CHtml::error("address", $event)}
    </p>

    <p>
        {CHtml::submit("Сохранить")}
    </p>

</form>