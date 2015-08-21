{extends file="_core.3col.tpl"}

{block name="asu_center"}
<h2>Добавление таксономии</h2>
{CHtml::helpForCurrentPage()}

{include file="_taxonomy/form.Taxonomy.tpl"}
{/block}

{block name="asu_right"}
{include file="_taxonomy/addTaxonomy.right.tpl"}
{/block}