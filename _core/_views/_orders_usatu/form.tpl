<script>
    jQuery(document).ready(function(){
        jQuery("#date").datepicker({
            dateFormat: "dd.mm.yy",
            showOn: "both",
            buttonImage: "{$web_root}css/_core/jUI/images/calendar.gif",
            buttonImageOnly: true
        });
    });
</script>

<form action="index.php" method="post" enctype="multipart/form-data" class="form-horizontal">
{CHtml::hiddenField("action", "save")}
{CHtml::activeHiddenField("id", $order)}

    <p>{CHtml::errorSummary($order)}</p>

    <div class="control-group">
        {CHtml::activeLabel("title", $order)}
        <div class="controls">
        {CHtml::activeTextField("title", $order)}
        {CHtml::error("title", $order)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("orders_type", $order)}
        <div class="controls">
        {CHtml::activeDropDownList("orders_type", $order, CTaxonomyManager::getUsatuOrderTypesList())}
        {CHtml::error("orders_type", $order)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("order_for_seb", $order)}
        <div class="controls">
        {CHtml::activeCheckBox("order_for_seb", $order)}
        {CHtml::error("order_for_seb", $order)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("order_num_date", $order)}
        <div class="controls">
        № {CHtml::activeTextField("num", $order, "", "", 'style="width: 100px;"')}
        {CHtml::error("num", $order)}

        от {CHtml::activeTextField("date", $order, "date", "", 'style="width: 100px;"')}
        {CHtml::error("date", $order)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("text", $order)}
        <div class="controls">
        {CHtml::activeTextBox("text", $order)}
        {CHtml::error("text", $order)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("comment", $order)}
        <div class="controls">
        {CHtml::activeTextBox("comment", $order)}
        {CHtml::error("comment", $order)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("attachment", $order)}
        <div class="controls">
            {CHtml::activeUpload("attachment", $order)}
            {CHtml::error("attachment", $order)}
        </div>
    </div>

    <div class="control-group">
        <div class="controls">
            {CHtml::submit("Сохранить")}
        </div>
    </div>
</form>