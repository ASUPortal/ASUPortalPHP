<form action="index.php" method="post">
    {CHtml::hiddenField("action", "save")}
    {if isset($resource)}
        {CHtml::hiddenField("id", $resource->getId())}
    {/if}

    <p>
        {CHtml::label("Название", "name")}
        {if isset($resource)}
            {CHtml::textField("name", $resource->getName())}
        {else}
            {CHtml::textField("name")}
        {/if}
    </p>

    <p>
        {CHtml::label("Тип", "type")}
        {if isset($resource)}
            {CHtml::dropDownList("type", CResourcesManager::getTypesList(), $resource->getType())}
        {else}
            {CHtml::dropDownList("type", CResourcesManager::getTypesList())}
        {/if}
    </p>

    <p>
        {CHtml::label("Значение", "resource_id")}
        {if isset($resource)}
            {CHtml::dropDownList("resource_id", CStaffManager::getPersonsList(), $resource->getResourceId())}
        {else}
            {CHtml::dropDownList("resource_id", CStaffManager::getPersonsList())}
        {/if}
    </p>

    <p>
        {CHtml::submit("Сохранить")}
    </p>
</form>