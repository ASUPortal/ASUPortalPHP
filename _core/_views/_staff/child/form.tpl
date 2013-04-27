<script>
    jQuery(document).ready(function(){
        jQuery("#birth_date").datepicker({
            dateFormat: "dd.mm.yy",
            showOn: "both",
            buttonImage: "{$web_root}css/_core/jUI/images/calendar.gif",
            buttonImageOnly: true,
            changeYear: true
        });
    });
</script>

<form action="children.php" method="post">
    {CHtml::hiddenField("action", "save")}
    {CHtml::activeHiddenField("id", $child)}
    {CHtml::activeHiddenField("kadri_id", $child)}

    <p>
        {CHtml::activeLabel("pol_id", $child)}
        {CHtml::activeDropDownList("pol_id", $child, CTaxonomyManager::getLegacyTaxonomy("pol")->getTermsList())}
        {CHtml::error("pol_id", $child)}
    </p>

    <p>
        {CHtml::activeLabel("birth_date", $child)}
        {CHtml::activeTextField("birth_date", $child, "birth_date")}
        {CHtml::error("birth_date", $child)}
    </p>

    <p>
        {CHtml::submit("Сохранить")}
    </p>
</form>