{extends file="_core.3col.tpl"}

{block name="asu_center"}
<h2>Редактирование термина таксономии</h2>

    <form action="index.php" class="form-horizontal">
        {CHtml::activeHiddenField("id", $term)}
        {CHtml::activeHiddenField("taxonomy_id", $term)}
        {CHtml::hiddenField("action", "saveTerm")}

        <div class="control-group">
            {CHtml::activeLabel("name", $term)}
            <div class="controls">
            {CHtml::activeTextField("name", $term)}
            {CHtml::error("name", $term)}
        </div></div>

        <div class="control-group">
            {CHtml::activeLabel("alias", $term)}
            <div class="controls">
            {CHtml::activeTextField("alias", $term)}
            {CHtml::error("alias", $term)}
        </div></div>        
        
        <div class="control-group">
            <div class="controls">
            {CHtml::submit("Сохранить")}
        </div></div>
    </form>
{/block}

{block name="asu_right"}
{include file="_taxonomy/edit.right.tpl"}
{/block}