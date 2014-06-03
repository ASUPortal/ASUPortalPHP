<div class="control-group">
    <div class="controls">
    {CHtml::activeLookup("group[users]", $form, "class.CSearchCatalogUsers", true)}
    {CHtml::error("group[users]", $form)}
    </div>
</div>