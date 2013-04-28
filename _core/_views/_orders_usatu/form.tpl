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

<form action="index.php" method="post" enctype="multipart/form-data">
{CHtml::hiddenField("action", "save")}
{CHtml::activeHiddenField("id", $order)}

    <p>{CHtml::errorSummary($order)}</p>

    <p>
        {CHtml::activeLabel("title", $order)}
        {CHtml::activeTextField("title", $order)}
        {CHtml::error("title", $order)}
    </p>

    <p>
        {CHtml::activeLabel("orders_type", $order)}
        {CHtml::activeDropDownList("orders_type", $order, CTaxonomyManager::getUsatuOrderTypesList())}
        {CHtml::error("orders_type", $order)}
    </p>

    <p>
        {CHtml::activeLabel("order_for_seb", $order)}
        {CHtml::activeCheckBox("order_for_seb", $order)}
        {CHtml::error("order_for_seb", $order)}
    </p>

    <p>
        {CHtml::activeLabel("order_num_date", $order)}
        № {CHtml::activeTextField("num", $order, "", "", 'style="width: 100px;"')}
        {CHtml::error("num", $order)}

        от {CHtml::activeTextField("date", $order, "date", "", 'style="width: 100px;"')}
        {CHtml::error("date", $order)}
    </p>

    <p>
        {CHtml::activeLabel("text", $order)}
        {CHtml::activeTextBox("text", $order)}
        {CHtml::error("text", $order)}
    </p>

    <p>
        {CHtml::activeLabel("comment", $order)}
        {CHtml::activeTextBox("comment", $order)}
        {CHtml::error("comment", $order)}
    </p>

    <p>
    {CHtml::submit("Сохранить")}
    </p>    
</form>