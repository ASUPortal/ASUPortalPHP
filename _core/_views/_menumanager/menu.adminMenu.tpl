{function name=menuItemsAsListWithCount level=0}
<ul class="level{$level}">
    {foreach $data as $entry}
        {if ($entry->getChilds()->getCount() > 0)}
            <li>
                <a href="{$entry->getLink()|htmlspecialchars}"><strong>{$entry->getName()|htmlspecialchars}</strong></a> ({$entry->getChilds()->getCount()})
                {call name=menuItemsAsListWithCount data=$entry->getChilds()->getItems() level=$level+1}
            </li>
            {else}
            <li>
                <a href="{$entry->getLink()|htmlspecialchars}"><strong>{$entry->getName()|htmlspecialchars}</strong></a>
            </li>
        {/if}
    {/foreach}
</ul>
{/function}

<div id="adminMenu">
    {include file="_menumanager/menu.mainWapMenu.tpl"}
    {call name=menuItemsAsListWithCount data=CMenuManager::getMenu("admin_menu")->getMenuPublishedItemsInHierarchy()->getItems()}
</div>