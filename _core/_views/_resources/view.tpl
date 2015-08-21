{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>{$resource->getName()}</h2>
    {CHtml::helpForCurrentPage()}

    <p>
        Название ресурса: {$resource->getName()}
    </p>

    <p>
        Родительский ресурс: {$resource->getResource()->getName()}
    </p>

    <h2>Календари</h2>

    <ul>
    {foreach $resource->getCalendars()->getItems() as $i}
        <li><a href="{$web_root}_modules/_calendar/?resource_id={$resource->getId()}&calendar_id={$i->getId()}">{$i->getName()}</a></li>
    {/foreach}
    </ul>
{/block}

{block name="asu_right"}
    {include file="_resources/view.right.tpl"}
{/block}