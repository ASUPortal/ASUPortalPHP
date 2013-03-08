<script>
    jQuery(document).ready(function(){
        jQuery("#default_readers").namesSelector();
        jQuery("#default_authors").namesSelector();
    });
</script>

<form action="tables.php" method="post" enctype="multipart/form-data">
{CHtml::hiddenField("action", "save")}
{CHtml::activeHiddenField("id", $table)}

    <p>{CHtml::errorSummary($table)}</p>

    <p>
        {CHtml::activeLabel("table", $table)}
        {CHtml::activeTextField("table", $table)}
        {CHtml::error("table", $table)}
    </p>

    <p>
        {CHtml::activeLabel("title", $table)}
        {CHtml::activeTextField("title", $table)}
        {CHtml::error("title", $table)}
    </p>

    <p>
        {CHtml::activeLabel("description", $table)}
        {CHtml::activeTextBox("description", $table)}
        {CHtml::error("description", $table)}
    </p>

    <h2>Доступ к записям таблицы по умолчанию</h2>

    <p>
        {CHtml::activeLabel("default_readers", $table)}
        {CHtml::activeNamesSelect("default_readers", $table)}
        {CHtml::error("default_readers", $table)}
    </p>

    <p>
        {CHtml::activeLabel("default_authors", $table)}
        {CHtml::activeNamesSelect("default_authors", $table)}
        {CHtml::error("default_authors", $table)}
    </p>

    {include file="_core.acl.tpl"}

    <p>
    {CHtml::submit("Сохранить")}
    </p>
</form>