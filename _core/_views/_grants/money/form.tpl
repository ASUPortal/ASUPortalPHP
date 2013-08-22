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

<form action="money.php" method="post" enctype="multipart/form-data" class="form-horizontal">
    {CHtml::activeHiddenField("id", $money)}
    {CHtml::activeHiddenField("period_id", $money)}
    {CHtml::hiddenField("action", "save")}

    <div class="control-group">
        {CHtml::activeLabel("type_id", $money)}
        <div class="controls">
        {CHtml::activeDropDownList("type_id", $money, $types, "type")}
        {CHtml::error("type_id", $money)}
    </div></div>

    <div class="control-group">
        {CHtml::activeLabel("value", $money)}
        <div class="controls">
        {CHtml::activeTextField("value", $money)}
        {CHtml::error("value", $money)}
    </div></div>

    <div id="category" class="control-group">
        {CHtml::activeLabel("category_id", $money)}
        <div class="controls">
        {CHtml::activeDropDownList("category_id", $money, CTaxonomyManager::getTaxonomy("outgo_categories")->getTermsList())}
        {CHtml::error("category_id", $money)}
    </div></div>

    <div class="control-group">
        {CHtml::activeLabel("comment", $money)}
        <div class="controls">
        {CHtml::activeTextBox("comment", $money)}
        {CHtml::error("comment", $money)}
    </div></div>

    <div class="control-group">
        <div class="controls">
        {CHtml::submit("Сохранить")}
    </div></div>

</form>