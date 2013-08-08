{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Импорт полей</h2>

    {CHtml::helpForCurrentPage()}

    <form action="models.php" method="post" class="form-horizontal">
        {CHtml::hiddenField("action", "saveImported")}
        {CHtml::activeHiddenField("id", $form)}

    <table class="table table-striped table-bordered table-hover table-condensed">
        <tr>
            <th>Поле</th>
            <th>Основной перевод</th>
            <th>Основной валидатор</th>
        </tr>
        {foreach $form->fields as $field}
            <tr>
                <td>{$field["name"]}</td>
                {CHtml::activeHiddenField("name", $form, $field["name"], $field["name"])}
                <td>{CHtml::activeTextField("translation", $form, "", "span5", "", $field["name"])}</td>
                <td>{CHtml::activeDropDownList("validator", $form, CCoreObjectsManager::getCoreValidatorsList(), "", "span5", "", $field["name"])}</td>
            </tr>
        {/foreach}
    </table>

    <div class="control-group">
        <div class="controls">
            {CHtml::submit("Импортировать", false)}
        </div>
    </div>

    </form>
{/block}

{block name="asu_right"}
    {include file="_core/model/import.right.tpl"}
{/block}