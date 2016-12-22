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
            <i title="Редактировать нагрузку" class="icon-pencil" onclick="window.open('{$web_root}_modules/_individual_plan/load.php?action=edit&id={$load->getId()}&year={$load->year->id}')" style="cursor: pointer; "></i>
            <i title="Скопировать нагрузку" class="icon-share" onclick="window.open('{$web_root}_modules/_individual_plan/load.php?action=selectYearLoad&id={$load->getId()}')" style="cursor: pointer; "></i>
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