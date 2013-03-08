<form action="index.php" method="post">
    {CHtml::hiddenField("action", "save")}
    {if (isset($menu)) }
        {CHtml::hiddenField("id", $menu->getId())}
    {/if}
    
    <p>
        {CHtml::label("Название", "title")}
        {if isset($menu)}
            {CHtml::textField("name", $menu->getName())}
        {else}
            {CHtml::textField("name")}
        {/if}
    </p>

    <p>
        {CHtml::label("Псевдоним", "alias")}
        {if isset($menu)}
            {CHtml::textField("alias", $menu->getAlias())}
            {else}
            {CHtml::textField("alias")}
        {/if}
    </p>

    <p>
        {CHtml::label("Описание", "description")}
        {if isset($menu)}
            {CHtml::textBox("description", $menu->getAlias())}
            {else}
            {CHtml::textBox("description")}
        {/if}
    </p>

    <p>
        {CHtml::label("Опубликован", "published")}
        {if isset($menu)}
            {CHtml::checkBox("published", "1", $menu->isPublished())}
            {else}
            {CHtml::checkBox("published", "1")}
        {/if}
    </p>    
    
    <p>
        {CHtml::submit("Сохранить")}
    </p>
</form>