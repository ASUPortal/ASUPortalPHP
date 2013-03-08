<div id="asu_wap_switch"></div>

<div id="mainMenu" class="sdmenu">
    {foreach CMenuManager::getMenu("main_menu")->getMenuPublishedItemsInHierarchy()->getItems() as $item}
        <div class="collapsed">
            {if ($item->getChilds()->getCount() > 0)}
                <span class="hasChild" title="Выберите подраздел">{$item->getName()}</span>
                {foreach $item->getChilds()->getItems() as $child}
                    <a class="hasChild" href="{$child->getLink()|htmlspecialchars}" title="{$child->getName()|htmlspecialchars}">{$child->getName()}</a>
                {/foreach}
            {else}
                <span class="noChild">
                    <a class="noChild" href="{$item->getLink()|htmlspecialchars}" title="{$item->getName()|htmlspecialchars}">{$item->getName()}</a>
                </span>
            {/if}
        </div>
    {/foreach}
</div>