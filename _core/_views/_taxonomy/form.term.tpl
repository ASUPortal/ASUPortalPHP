<form action="index.php" class="form-horizontal">
    {CHtml::activeHiddenField("id", $term)}
    {CHtml::activeHiddenField("taxonomy_id", $term)}
    {CHtml::hiddenField("action", "saveTerm")}

    {if $term->getParentTaxonomy()->alias == "corriculum_competentions"}
        {include file="_taxonomy/form.term.alias.competentions.tpl"}
    {else}
        {include file="_taxonomy/form.term.alias.default.tpl"}
    {/if}

    <div class="control-group">
        <div class="controls">
            {CHtml::submit("Сохранить")}
        </div>
    </div>
</form>