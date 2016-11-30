<p><b>Нагрузка на
    {if (!is_null(CTaxonomyManager::getYear($year)))}
        {CTaxonomyManager::getYear($year)->getValue()}
    {/if}
учебный год</b></p>

<ul class="nav nav-tabs">
{foreach from=$yearLoad->getItems() item=load name=loadItem}
    <li {if $smarty.foreach.loadItem.first}class="active"{/if}>
        <a href="#load_{$load->getId()}" data-toggle="tab">
            {$load->getType()}
            <i class="icon-pencil" onclick="window.location.href='?action=edit&id={$load->getId()}&year={$load->year->id}'" style="cursor: pointer; "></i>
        </a>
    </li>
{/foreach}
</ul>

<div class="tab-content">
{foreach from=$yearLoad->getItems() item=load name=loadItem}
    <div id="load_{$load->getId()}" class="tab-pane {if $smarty.foreach.loadItem.first}active{/if}">
        {include file="_individual_plan/load/subform.load.tpl"}
    </div>
{/foreach}
</div>