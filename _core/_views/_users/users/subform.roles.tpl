<script>
    jQuery(document).ready(function(){
        var groups = {$fromGroups};
        jQuery.each(groups, function(key, value){
            var inputs = jQuery("#roles").find("input[value=" + key + "]");
            jQuery.each(inputs, function(key, input) {
                jQuery(input).prop("disabled", true);
            });
        });
    });
</script>

<p>
    Права, полученные от групп недоступны для удаления
</p>

<p id="roles">
    {CHtml::activeCheckBoxGroup("user[roles]", $form, CStaffManager::getAllUserRolesList())}
    {CHtml::error("user[roles]", $form)}
</p>