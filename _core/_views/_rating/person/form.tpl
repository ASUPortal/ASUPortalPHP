<script>
    jQuery(document).ready(function(){
        jQuery("#indexes_accordion").accordion({ autoHeight: false });
    });

    function getIndexesByYear(year) {
        {if is_null($form->person_id)}
            location.href="persons.php?action=add&year=" + year;
        {else}
            location.href="persons.php?action=edit&id={$form->person_id}&year=" + year;
        {/if}
    }
</script>

<form action="persons.php" method="post">
    {CHtml::hiddenField("action", "save")}

    {CHtml::errorSummary($form)}

    <p>
        {CHtml::activeLabel("person_id", $form)}
        {CHtml::activeDropDownList("person_id", $form, CStaffManager::getPersonsList())}
        {CHtml::error("person_id", $form)}
        {CHtml::personTypeFilter("person_id", $form)}
    </p>

    <p>
        {CHtml::activeLabel("year_id", $form)}
        {CHtml::activeDropDownList("year_id", $form, CTaxonomyManager::getYearsList(), "", "", "onchange='getIndexesByYear(this.value)'")}
        {CHtml::error("year_id", $form)}
    </p>

    <p>
        {CHtml::activeLabel("indexes", $form)}
        <div id="indexes_accordion" style="margin-left: 200px;">
            {foreach CRatingManager::getRatingIndexesByYear($year)->getItems() as $item}
                <h3><a href="#">{$item->title}</a></h3>
                <div>
                    {if $item->isMultivalue()}
                        {CHtml::activeCheckBoxGroup("indexes", $form, $item->getAvailableIndexValuesList())}
                    {else}
                        {CHtml::activeRadioButtonGroup("indexes", $form, $item->getAvailableIndexValuesList(), $item->getId())}
                    {/if}
                </div>
            {/foreach}
        </div>
        {CHtml::error("indexes", $form)}
    </p>

    <div class="control-group">
        <div class="controls">
            {CHtml::submit("Сохранить")}
        </div></div>
</form>