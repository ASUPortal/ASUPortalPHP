{extends file="_core.3col.tpl"}

{block name="asu_center"}
<script>
    jQuery(document).ready(function(){
       jQuery("#tabs").tabs();
    });
</script>

    <h2>Справочники: правка</h2>
    {CHtml::helpForCurrentPage()}

    Просмотр справочника {CHtml::dropDownList("taxonomy_id", CTaxonomyManager::getLegacyTaxonomiesObjectsList(), $taxonomy->getId(), "taxonomy_id")}

<div id="tabs">
    <ul style="height: 30px; ">
        <li><a href="#tab-list">Термины таксономии</a></li>
        <li><a href="#tab-common">Общая информация</a></li>
    </ul>
    <div id="tab-list">
        {if $taxonomy->getTableName() == "spravochnik_uch_rab"}
            {include file="_taxonomy/subform.termsLegacy.workTypes.tpl"}
        {else}
            {include file="_taxonomy/subform.termsLegacy.tpl"}
        {/if}
    </div>
    <div id="tab-common">
        {include file="_taxonomy/form.legacy.tpl"}
    </div>
</div>

{/block}

{block name="asu_right"}
    {include file="_taxonomy/legacy.right.tpl"}
{/block}
