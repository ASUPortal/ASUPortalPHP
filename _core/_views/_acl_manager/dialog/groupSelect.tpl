<div style="border: 1px solid #c0c0c0; width: 470px; height: 280px; overflow-y: scroll; " id="groupSelectDialog">
{foreach $items->getItems() as $group}
    <span><input type="checkbox" value="{$group->getId()}" rus="{$group->getName()}">{$group->getName()}</span><br>
{/foreach}
</div>