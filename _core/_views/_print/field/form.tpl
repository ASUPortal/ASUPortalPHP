<form action="field.php" method="post" enctype="multipart/form-data">
{CHtml::hiddenField("action", "save")}
{CHtml::activeHiddenField("id", $field)}

    <p>{CHtml::errorSummary($field)}</p>

    <p>
        {CHtml::activeLabel("title", $field)}
        {CHtml::activeTextField("title", $field)}
        {CHtml::error("title", $field)}
    </p>

    <p>
        {CHtml::activeLabel("alias", $field)}
        {CHtml::activeTextField("alias", $field)}
        {CHtml::error("alias", $field)}
    </p>

    <p>
        {CHtml::activeLabel("description", $field)}
        {CHtml::activeTextBox("description", $field)}
        {CHtml::error("description", $field)}
    </p>

    <p>
        {CHtml::activeLabel("parent_id", $field)}
        {CHtml::activeDropDownList("parent_id", $field, $fields)}
        {CHtml::error("parent_id", $field)}
    </p>

    <p>
        {CHtml::activeLabel("formset_id", $field)}
        {CHtml::activeDropDownList("formset_id", $field, $formsets)}
        {CHtml::error("formset_id", $field)}
    </p>

    <p>
        {CHtml::activeLabel("type_id", $field)}
        {CHtml::activeDropDownList("type_id", $field, $types)}
        {CHtml::error("type_id", $field)}
    </p>

    {if CRequest::getString("action") == "edit"}
    <p>
        {CHtml::activeLabel("parent_node", $field)}
        {CHtml::activeDropDownList("parent_node", $field, $parents)}
        {CHtml::error("parent_node", $field)}
    </p>
    {/if}

    <p>
        {CHtml::activeLabel("value_evaluate", $field)}
        {CHtml::activeTextBox("value_evaluate", $field)}
        {CHtml::error("value_evaluate", $field)}
    </p>

    <p>
    {CHtml::submit("Сохранить")}
    </p>    
</form>