<p>
    Мои календари:
    {if $resource->getCalendars()->getCount() > 1}
        {CHtml::hiddenField("resource_id", $resource->getId())}
        {CHtml::dropDownList("calendar_id", $resource->getCalendarsList(), CRequest::getInt("calendar_id"), null, null, 'onchange="onCalendarChange(); "')}
    {/if}
</p>

{include file="_menumanager/menu.mainMenu.tpl"}