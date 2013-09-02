<script>
    jQuery(document).ready(function(){
        var groups = {$fromGroups};
        jQuery.each(groups, function(key, value){
            var inputs = jQuery("#roles").find("select[name='CModel[user][roles][" + key + "]']");
            jQuery.each(inputs, function(key, input) {
                jQuery(input).prop("disabled", true);
            });
        });
    });
</script>

<p>
    Права, полученные от групп недоступны для изменения
</p>

<div class="control-group">
    <div class="controls" id="roles">
    {CHtml::actionUserRolesSelector("user[roles]", $form)}
    {CHtml::error("user[roles]", $form)}
    </div>
</div>