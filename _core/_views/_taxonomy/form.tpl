<form action="index.php" method="post" class="form-horizontal">
    {CHtml::hiddenField("action", "save")}
    {if isset($taxonomy)}
        {CHtml::hiddenField("taxonomy_id", $taxonomy->getId())}
    {/if}

    <div class="control-group">
        {CHtml::label("Значение", "name")}
        <div class="controls">
            {if isset($term)}
            {CHtml::textField("name", $term->getName())}
            {else}
            {CHtml::textField("name")}
        {/if}
    </div></div>

    <div class="control-group">
        {CHtml::label("Псевдоним", "alias")}
        <div class="controls">
            {if isset($term)}
            {CHtml::textField("alias", $term->getAlias())}
            {else}
            {CHtml::textField("alias")}
        {/if}
    </div></div>

    <div class="control-group">
        <div class="controls">
    {CHtml::submit("Сохранить")}
    </div></div>
</form>