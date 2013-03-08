{extends file="_core.3col.tpl"}

{block name="asu_left"}
    {include file="_calendar/index.left.tpl"}
{/block}

{block name="asu_center"}
    <h2>{$resource->getName()}</h2>
    <div id="fullCalendar"></div>
{/block}

{block name="asu_right"}
    {include file="_calendar/index.right.tpl"}
{/block}