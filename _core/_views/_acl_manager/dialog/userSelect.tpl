<div style="border: 1px solid #c0c0c0; width: 470px; height: 280px; overflow-y: scroll; " id="userSelectDialog">
    {foreach $items->getItems() as $person}
        <span><input type="checkbox" value="{$person->getId()}" rus="{$person->getName()}">{$person->getName()}</span><br>
    {/foreach}
</div>