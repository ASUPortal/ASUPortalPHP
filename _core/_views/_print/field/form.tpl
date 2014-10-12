<form action="field.php" method="post" class="form-horizontal" enctype="multipart/form-data">
{CHtml::hiddenField("action", "save")}
{CHtml::activeHiddenField("id", $field)}

    {CHtml::errorSummary($field)}

    <div class="control-group">
        {CHtml::activeLabel("title", $field)}
        <div class="controls">
        {CHtml::activeTextField("title", $field)}
        {CHtml::error("title", $field)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("alias", $field)}
        <div class="controls">
        {CHtml::activeTextField("alias", $field)}
        {CHtml::error("alias", $field)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("description", $field)}
        <div class="controls">
        {CHtml::activeTextBox("description", $field)}
        {CHtml::error("description", $field)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("parent_id", $field)}
        <div class="controls">
        {CHtml::activeDropDownList("parent_id", $field, $fields)}
        {CHtml::error("parent_id", $field)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("formset_id", $field)}
        <div class="controls">
        {CHtml::activeDropDownList("formset_id", $field, $formsets)}
        {CHtml::error("formset_id", $field)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("type_id", $field)}
        <div class="controls">
        {CHtml::activeDropDownList("type_id", $field, $types)}
        {CHtml::error("type_id", $field)}
        </div>
    </div>

    {if CRequest::getString("action") == "edit"}
        {if $field->children->getCount() > 0}
    <div class="control-group">
        {CHtml::activeLabel("parent_node", $field)}
        <div class="controls">
        {CHtml::activeDropDownList("parent_node", $field, $parents)}
        {CHtml::error("parent_node", $field)}
        </div>
    </div>
        {/if}
    {/if}

    <div class="control-group">
        {CHtml::activeLabel("value_evaluate", $field)}
        <div class="controls">
        {CHtml::activeTextBox("value_evaluate", $field)}
        {CHtml::error("value_evaluate", $field)}
        </div>
    </div>

    <div class="control-group">
        <div class="controls">
        {CHtml::submit("Сохранить")}
        </div>
    </div>
</form>