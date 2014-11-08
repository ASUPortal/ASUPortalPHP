<div class="control-group">
    <div class="controls" id="roles">
    {CHtml::actionUserRolesSelector("user[roles]", $form)}
    {CHtml::error("user[roles]", $form)}
    </div>
</div>

<script>
    jQuery(document).ready(function(){
        jQuery(".roleDisabler").on("change", function(){
            var role = jQuery(this).val();
            var isSelected = jQuery(this).is(":checked");
            /**
             * Включаем/выключаем соответствующий выбиратор роли
             */
            jQuery('[asu-attr=role_' + role + ']').each(function(key, value){
                jQuery(value).attr("disabled", isSelected);
            });
        });
        /**
         * Выключаем задачи, доступа к которым у пользователя нет
         * (он не получил их лично или от групп)
         * Если мы этого не сделаем, то дальше задачи будут уже
         * заблокированы через личные права
         */
        jQuery('.roleDisabler:checked').each(function(key, value){
            var role = jQuery(value).val();
            jQuery('[asu-attr=role_' + role + ']').each(function(key, value){
                jQuery(value).attr("disabled", true);
            });
        });
        /**
         * Выключаем роли, полученные из групп
         */
        var fromGroups = {$fromGroups};
        jQuery(Object.keys(fromGroups)).each(function(key, value){
            jQuery('[asu-attr=role_' + value + ']').each(function(index, element){
                jQuery(element).attr("disabled", true);
            });
            jQuery('[asu-attr=disabler_' + value + ']').each(function(index, element){
                jQuery(element).attr("checked", true);
            });
        });
    });
</script>