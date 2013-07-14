<script>
    jQuery(document).ready(function(){
        jQuery('.dropdown-toggle').dropdown()
    });
</script>

<ul class="nav nav-tabs nav-stacked">
    {foreach CMenuManager::getMenu("main_menu")->getMenuPublishedItemsInHierarchy()->getItems() as $item}
        {if ($item->getChilds()->getCount() > 0)}
            <li class="dropdown-submenu">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                    {$item->getName()}
                </a>
                <ul class="dropdown-menu">
                {foreach $item->getChilds()->getItems() as $child}
                    <li><a tabindex="-1" title="{$child->getName()|htmlspecialchars}" href="{$child->getLink()|htmlspecialchars}">{$child->getName()}</a></li>
                {/foreach}
                </ul>
            </li>
        {else}
            <li><a tabindex="-1" title="{$item->getName()|htmlspecialchars}" href="{$item->getLink()|htmlspecialchars}">{$item->getName()}</a></li>
        {/if}
    {/foreach}
</ul>