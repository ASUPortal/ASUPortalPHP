<form action="index.php" method="post">
    {CHtml::hiddenField("action", "SaveCalendar")}
    
    <p>
        {CHtml::label("Название", "name")}
        {if isset($calendar)}
            {CHtml::textField("name", $calendar->getName())}
        {else}
            {CHtml::textField("name")}
        {/if}
    </p>
    
    <p>
        {CHtml::label("Описание", "description")}
        {if isset($calendar)}
            {CHtml::textBox("description", $calendar->getDescription())}
            {else}
            {CHtml::textBox("description")}
        {/if}
    </p>
    
    <p>
        {CHtml::label("Ресурс", "resource_id")}
        {if isset($calendar)}
            {CHtml::textBox("description", $calendar->getDescription())}
            {else}
            {CHtml::dropDownList("resource_id", CResourcesManager::getResourcesList())}
        {/if}
    </p>
    
    <p>
        {CHtml::submit("Сохранить")}
    </p>
</form>