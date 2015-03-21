<div class="control-group">
    {CHtml::activeLabel("name", $term)}
    <div class="controls">
        {CHtml::activeTextField("name", $term)}
        {CHtml::error("name", $term)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("alias", $term)}
    <div class="controls">
        {CHtml::activeTextField("alias", $term)}
        {CHtml::error("alias", $term)}
    </div>
</div>

{if !is_null($term->getParentTaxonomy())}
    {if !is_null($term->getParentTaxonomy()->childTaxonomy)}
        <div class="control-group">
            {CHtml::activeLabel("childTerms", $term)}
            <div class="controls">
                {CHtml::activeLookup("childTerms", $term, $term->getParentTaxonomy()->childTaxonomy->getAlias(), true)}
                {CHtml::error("childTerms", $term)}
            </div>
        </div>
    {/if}
{/if}