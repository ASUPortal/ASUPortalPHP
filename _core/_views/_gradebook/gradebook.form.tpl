<script>
    jQuery(document).ready(function(){
        jQuery("#date_start").datepicker({
            dateFormat: "dd.mm.yy",
            showOn: "button",
            buttonImage: "{$web_root}css/_core/jUI/images/calendar.gif",
            buttonImageOnly: true
        });
        jQuery("#date_end").datepicker({
            dateFormat: "dd.mm.yy",
            showOn: "button",
            buttonImage: "{$web_root}css/_core/jUI/images/calendar.gif",
            buttonImageOnly: true
        });
    });
</script>

<form action="index.php" method="post">
{CHtml::hiddenField("action", "saveGradebook")}

    <p>{CHtml::errorSummary($search)}</p>

    <p>
        {CHtml::activeLabel("date_start", $search)}
        {CHtml::activeTextField("date_start", $search, "date_start")}
        {CHtml::error("date_start", $search)}
    </p>

    <p>
        {CHtml::activeLabel("date_end", $search)}
        {CHtml::activeTextField("date_end", $search, "date_end")}
        {CHtml::error("date_end", $search)}
    </p>

    <p>
        {CHtml::activeLabel("subject_id", $search)}
        {CHtml::activeDropDownList("subject_id", $search, CTaxonomyManager::getDisciplinesList(), "subject_id")}
        {CHtml::error("subject_id", $search)}
    </p>

    <p>
        {CHtml::activeLabel("kadri_id", $search)}
        {CHtml::activeDropDownList("kadri_id", $search, CStaffManager::getPersonsList(), kadri_id)}
        {CHtml::personTypeFilter("kadri_id", $search)}
        {CHtml::error("kadri_id", $search)}
    </p>

    <p>
        {CHtml::activeLabel("group_id", $search)}
        {CHtml::activeDropDownList("group_id", $search, $groups, "group_id")}
        {CHtml::error("group_id", $search)}
    </p>

    <p>
    {CHtml::submit("Поиск")}
    </p>
</form>