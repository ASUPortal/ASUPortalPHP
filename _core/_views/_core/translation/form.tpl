<form action="translations.php" method="post" class="form-horizontal">
    {CHtml::hiddenField("action", "save")}
    {CHtml::activeHiddenField("id", $translation)}
    {CHtml::activeHiddenField("field_id", $translation)}

    {CHtml::errorSummary($translation)}

    <div class="control-group">
        {CHtml::activeLabel("language_id", $translation)}
        <div class="controls">
            {CHtml::activeDropDownList("language_id", $translation, CTaxonomyManager::getLegacyTaxonomy("language")->getTermsList())}
            {CHtml::error("language_id", $translation)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("value", $translation)}
        <div class="controls">
            {CHtml::activeTextField("value", $translation)}
            {CHtml::error("value", $translation)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("table_value", $translation)}
        <div class="controls">
            {CHtml::activeTextField("table_value", $translation)}
            {CHtml::error("table_value", $translation)}
        </div>
    </div>
    
    <div class="control-group">
        <div class="controls">
            {CHtml::submit("Сохранить")}
        </div>
    </div>
</form>