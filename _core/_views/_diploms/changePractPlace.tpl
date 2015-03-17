{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Смена места практики</h2>

    {CHtml::helpForCurrentPage()}

    <form action="index.php" method="post" class="form-horizontal">
        {CHtml::hiddenField("action", "changePractPlaceProcess")}
        {CHtml::activeHiddenField("diploms", $form)}

        {CHtml::errorSummary($form)}

        <div class="control-group">
            {CHtml::activeLabel("pract_place_id", $form)}
            <div class="controls">
				{CHtml::activeLookup("pract_place_id", $form, "pract_places")}
				{CHtml::error("pract_place_id", $form)}
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