<script>
    jQuery(document).ready(function(){
        jQuery("#date_start").datepicker({
            dateFormat: "dd.mm.yy",
            showOn: "both",
            buttonImage: "{$web_root}css/_core/jUI/images/calendar.gif",
            buttonImageOnly: true
        });
        jQuery("#date_end").datepicker({
            dateFormat: "dd.mm.yy",
            showOn: "both",
            buttonImage: "{$web_root}css/_core/jUI/images/calendar.gif",
            buttonImageOnly: true
        });
    });
</script>

<form action="periods.php" method="post" enctype="multipart/form-data">
    {CHtml::activeHiddenField("id", $period)}
    {CHtml::activeHiddenField("grant_id", $period)}
    {CHtml::hiddenField("action", "save")}

    <p>
        {CHtml::activeLabel("title", $period)}
        {CHtml::activeTextField("title", $period)}
        {CHtml::error("title", $period)}
    </p>

    <p>
        {CHtml::activeLabel("date_start", $period)}
        {CHtml::activeTextField("date_start", $period, "date_start")}
        {CHtml::error("date_start", $period)}
    </p>

    <p>
        {CHtml::activeLabel("date_end", $period)}
        {CHtml::activeTextField("date_end", $period, "date_end")}
        {CHtml::error("date_end", $period)}
    </p>

    <p>
        {CHtml::activeLabel("comment", $period)}
        {CHtml::activeTextBox("comment", $period)}
        {CHtml::error("comment", $period)}
    </p>

    <p>
        {CHtml::submit("Сохранить")}
    </p>
</form>