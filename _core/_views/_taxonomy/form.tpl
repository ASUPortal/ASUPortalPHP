<form action="index.php" method="post">
    {CHtml::hiddenField("action", "save")}
    {if isset($taxonomy)}
        {CHtml::hiddenField("taxonomy_id", $taxonomy->getId())}
    {/if}

    <p>
        {CHtml::label("Значение", "name")}
            {if isset($term)}
            {CHtml::textField("name", $term->getName())}
            {else}
            {CHtml::textField("name")}
        {/if}
    </p>

    <p>
        {CHtml::label("Псевдоним", "alias")}
            {if isset($term)}
            {CHtml::textField("alias", $term->getAlias())}
            {else}
            {CHtml::textField("alias")}
        {/if}
    </p>

    <p>
    {CHtml::submit("Сохранить")}
    </p>
</form>