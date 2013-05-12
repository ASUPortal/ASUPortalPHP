<script>
    jQuery(document).ready(function(){
        if (jQuery("#type").val() == "1") {
            jQuery("#category").css("display", "none");
        }
        jQuery("#type").change(function(){
            if (jQuery("#type").val() == "1") {
                jQuery("#category").hide("slow");
            } else {
                jQuery("#category").show("slow");
            }
        });
    });
</script>

<form action="money.php" method="post" enctype="multipart/form-data">
    {CHtml::activeHiddenField("id", $money)}
    {CHtml::activeHiddenField("period_id", $money)}
    {CHtml::hiddenField("action", "save")}

    <p>
        {CHtml::activeLabel("type_id", $money)}
        {CHtml::activeDropDownList("type_id", $money, $types, "type")}
        {CHtml::error("type_id", $money)}
    </p>

    <p>
        {CHtml::activeLabel("value", $money)}
        {CHtml::activeTextField("value", $money)}
        {CHtml::error("value", $money)}
    </p>

    <p id="category">
        {CHtml::activeLabel("category_id", $money)}
        {CHtml::activeDropDownList("category_id", $money, CTaxonomyManager::getTaxonomy("outgo_categories")->getTermsList())}
        {CHtml::error("category_id", $money)}
    </p>

    <p>
        {CHtml::activeLabel("comment", $money)}
        {CHtml::activeTextBox("comment", $money)}
        {CHtml::error("comment", $money)}
    </p>

    <p>
        {CHtml::submit("Сохранить")}
    </p>

</form>