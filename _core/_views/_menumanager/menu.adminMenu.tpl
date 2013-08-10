{function name=menuItemsAsListWithCount level=0}
<ul class="{if $level == 0}nav{else}dropdown-menu{/if}">
    {foreach $data as $entry}
        {if ($entry->getChilds()->getCount() > 0)}
            <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="{$entry->getLink()|htmlspecialchars}">{$entry->getName()|htmlspecialchars}</a>
                {call name=menuItemsAsListWithCount data=$entry->getChilds()->getItems() level=$level+1}
            </li>
        {else}
            {if $level > 0 || $entry->getId() == 200000}
            <li>
                {if {$entry->getName()} == "<hr />"}
                    <a class="divider"></a>
                {else}
                <a href="{$entry->getLink()|htmlspecialchars}">{$entry->getName()|htmlspecialchars}</a>
                {/if}
            </li>
            {/if}
        {/if}
    {/foreach}
</ul>
{/function}

<div class="navbar">
    <div class="navbar-inner">
        <ul class="nav">
            <li>
                <p class="navbar-text">
                    <a href="#" id="asu_menu_hider" class="icon-th-list"></a>
                </p>
            </li>
        </ul>
        {call name=menuItemsAsListWithCount data=CMenuManager::getMenu("admin_menu")->getMenuPublishedItemsInHierarchy()->getItems()}
    </div>
</div>