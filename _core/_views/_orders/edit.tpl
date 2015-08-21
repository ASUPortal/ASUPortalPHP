{extends file="_core.3col.tpl"}

{block name="asu_center"}
<h2>Редактирование приказа</h2>
{CHtml::helpForCurrentPage()}

<script>
	jQuery(document).ready(function(){
		jQuery("#date_order").datepicker({
			dateFormat: "dd.mm.yy",
			showOn: "button",
            buttonImage: "{$web_root}css/_core/jUI/images/calendar.gif",
            buttonImageOnly: true,
            changeYear: true
		});
		jQuery("#date_begin").datepicker({
			dateFormat: "dd.mm.yy",
			showOn: "button",
            buttonImage: "{$web_root}css/_core/jUI/images/calendar.gif",
            buttonImageOnly: true,
            changeYear: true
		});
		jQuery("#date_end").datepicker({
			dateFormat: "dd.mm.yy",
			showOn: "button",
            buttonImage: "{$web_root}css/_core/jUI/images/calendar.gif",
            buttonImageOnly: true,
            changeYear: true
		});
	});
</script>

<form action="index.php" method="post" class="form-horizontal">
    <input type="hidden" name="action" value="save">
    {CHtml::activeHiddenField("id", $order)}
    {CHtml::activeHiddenField("kadri_id", $order)}

    <div class="control-group">
        {CHtml::activeLabel("type_money", $order)}
        <div class="controls">
        {CHtml::activeDropDownList("type_money", $order, $type_money)}
        {CHtml::error("type_money", $order)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("type_order", $order)}
        <div class="controls">
        {CHtml::activeDropDownList("type_order", $order, $type_order)}
        {CHtml::error("type_order", $order)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("order", $order)}
        <div class="controls">
        № {CHtml::activeTextField("num_order", $order, "", "", 'style="width: 100px;"')}
        {CHtml::error("num_order", $order)}

        от {CHtml::activeTextField("date_order", $order, "date_order", "", 'style="width: 100px;"')}
        {CHtml::error("date_order", $order)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("order_period", $order)}
        <div class="controls">
        с {CHtml::activeTextField("date_begin", $order, "date_begin", "", 'style="width: 100px;"')}
        {CHtml::error("date_begin", $order)}

        по {CHtml::activeTextField("date_end", $order, "date_end", "", 'style="width: 100px;"')}
        {CHtml::error("date_end", $order)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("order_active", $order)}
        <div class="controls">
            {CHtml::activeCheckBox("order_active", $order)}
            {CHtml::error("order_active", $order)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("main_work_place", $order)}
        <div class="controls">
        {CHtml::activeTextField("main_work_place", $order)}
        {CHtml::error("main_work_place", $order)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("prev_order", $order)}
        <div class="controls">
        {CHtml::activeTextField("prev_order", $order)}
        {CHtml::error("prev_order", $order)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("etc", $order)}
        <div class="controls">
        ЕТС {CHtml::activeTextField("rank_ets", $order, "", "", 'style="width: 100px;"')}
        {CHtml::error("rank_ets", $order)}

        ставка {CHtml::activeTextField("rate", $order, "", "", 'style="width: 100px;"')}
        {CHtml::error("rate", $order)}
        </div>
    </div>

    <div class="control-group">
        {CHtml::activeLabel("conditions", $order)}
        <div class="controls">
        {CHtml::activeTextField("conditions", $order)}
        {CHtml::error("conditions", $order)}
        </div>
    </div>

    <div class="control-group">
        <div class="controls">
        {CHtml::submit("Сохранить")}
        </div>
    </div>
</form>
{/block}

{block name="asu_right"}
{include file="_orders/edit.right.tpl"}
{/block}