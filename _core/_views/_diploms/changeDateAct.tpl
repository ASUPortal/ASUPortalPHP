{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Смена даты защиты</h2>

    {CHtml::helpForCurrentPage()}

    <form action="index.php" method="post" class="form-horizontal">
        {CHtml::hiddenField("action", "changeDateActProcess")}
        {CHtml::activeHiddenField("diploms", $form)}

        {CHtml::errorSummary($form)}

        <div class="control-group">
            {CHtml::activeLabel("date_act", $form)}
            <div class="controls">
				{CHtml::activeDateField("date_act", $form)}
				{CHtml::error("date_act", $form)}
            </div>
        </div>

        <div class="control-group">
            <div class="controls">
                {CHtml::submit("Сменить", false)}
            </div>
        </div>
    </form>
{/block}

{block name="asu_right"}
    {include file="_diploms/index.right.tpl"}
{/block}