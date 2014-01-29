{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Импорт студентов</h2>

    {CHtml::helpForCurrentPage()}

    <form action="index.php" method="post" class="form-horizontal">
        {CHtml::hiddenField("action", "changeGroupProcess")}
        {CHtml::activeHiddenField("students", $form)}

        {CHtml::errorSummary($form)}

        <div class="control-group">
            {CHtml::activeLabel("group_id", $form)}
            <div class="controls">
                {CHtml::activeLookup("group_id", $form, "studentgroup")}
                {CHtml::error("group_id", $form)}
            </div>
        </div>

        <div class="control-group">
            <div class="controls">
                {CHtml::submit("Перенести", false)}
            </div>
        </div>
    </form>
{/block}

{block name="asu_right"}
    {include file="_students/common.right.tpl"}
{/block}