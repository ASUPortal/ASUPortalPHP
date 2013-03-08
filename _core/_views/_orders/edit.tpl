{extends file="_core.3col.tpl"}

{block name="asu_center"}
<h2>Редактирование приказа</h2>

<script>
	jQuery(document).ready(function(){
		jQuery("#date_order").datepicker({
			dateFormat: "dd.mm.yy",
			showOn: "button",
            buttonImage: "{$web_root}css/_core/jUI/images/calendar.gif",
            buttonImageOnly: true
		});
		jQuery("#date_begin").datepicker({
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
    <input type="hidden" name="action" value="save">
    {CHtml::activeHiddenField("id", $order)}
    {CHtml::activeHiddenField("kadri_id", $order)}

    <p>
        {CHtml::activeLabel("type_money", $order)}
        {CHtml::activeDropDownList("type_money", $order, $type_money)}
        {CHtml::error("type_money", $order)}
    </p>

    <p>
        {CHtml::activeLabel("type_order", $order)}
        {CHtml::activeDropDownList("type_order", $order, $type_order)}
        {CHtml::error("type_order", $order)}
    </p>

    <p>
        {CHtml::activeLabel("order", $order)}
        № {CHtml::activeTextField("num_order", $order, "", "", 'style="width: 100px;"')}
        {CHtml::error("num_order", $order)}

        от {CHtml::activeTextField("date_order", $order, "date_order", "", 'style="width: 100px;"')}
        {CHtml::error("date_order", $order)}
    </p>

    <p>
        {CHtml::activeLabel("order_period", $order)}
        с {CHtml::activeTextField("date_begin", $order, "date_begin", "", 'style="width: 100px;"')}
        {CHtml::error("num_order", $order)}

        по {CHtml::activeTextField("date_end", $order, "date_end", "", 'style="width: 100px;"')}
        {CHtml::error("date_order", $order)}
    </p>

    <p>
        {CHtml::activeLabel("main_work_place", $order)}
        {CHtml::activeTextField("main_work_place", $order)}
        {CHtml::error("main_work_place", $order)}
    </p>

    <p>
        {CHtml::activeLabel("prev_order", $order)}
        {CHtml::activeTextField("prev_order", $order)}
        {CHtml::error("prev_order", $order)}
    </p>

    <p>
        {CHtml::activeLabel("etc", $order)}
        ЕТС {CHtml::activeTextField("rank_ets", $order, "", "", 'style="width: 100px;"')}
        {CHtml::error("rank_ets", $order)}

        ставка {CHtml::activeTextField("rate", $order, "", "", 'style="width: 100px;"')}
        {CHtml::error("rate", $order)}
    </p>

    <p>
        {CHtml::activeLabel("conditions", $order)}
        {CHtml::activeTextField("conditions", $order)}
        {CHtml::error("conditions", $order)}
    </p>

    <p>
        {CHtml::submit("Сохранить")}
    </p>
</form>
{/block}

{block name="asu_right"}
{include file="_orders/edit.right.tpl"}
{/block}