{extends file="_core.3col.tpl"}

{block name="asu_center"}
<h2>Редактирование термина таксономии</h2>

    <form action="index.php">
        {CHtml::activeHiddenField("id", $term)}
        {CHtml::activeHiddenField("taxonomy_id", $term)}
        {CHtml::hiddenField("action", "saveTerm")}

        <p>
            {CHtml::activeLabel("name", $term)}
            {CHtml::activeTextField("name", $term)}
            {CHtml::error("name", $term)}
        </p>

        <p>
            {CHtml::activeLabel("alias", $term)}
            {CHtml::activeTextField("alias", $term)}
            {CHtml::error("alias", $term)}
        </p>        
        
        <p>
            {CHtml::submit("Сохранить")}
        </p>
    </form>
{/block}

{block name="asu_right"}
{include file="_taxonomy/edit.right.tpl"}
{/block}