{extends file="_core.3col.tpl"}

{block name="asu_center"}
<h2>Справочники: правка</h2>

Просмотр справочника {CHtml::dropDownList("taxonomy_id", CTaxonomyManager::getTaxonomiesList(), $taxonomy->getId(), "taxonomy_id")}


<ul class="nav nav-tabs">
    <li class="active"><a href="#tab-list" data-toggle="tab">Термины таксономии</a></li>
    <li><a href="#tab-common" data-toggle="tab">Общая информация</a></li>
</ul>
<div class="tab-content">
    <div id="tab-list" class="tab-pane active">
        {if $taxonomy->alias == "corriculum_competentions"}
            {include file="_taxonomy/subform.terms.competentions.tpl"}
        {else}
            {include file="_taxonomy/subform.terms.tpl"}
        {/if}
    </div>
    <div id="tab-common" class="tab-pane">
        {include file="_taxonomy/form.Taxonomy.tpl"}
    </div>
</div>
{/block}

{block name="asu_right"}
{include file="_taxonomy/index.right.tpl"}
{/block}