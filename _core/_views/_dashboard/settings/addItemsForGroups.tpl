{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Добавление элемента для группы: {CStaffManager::getUserGroup(CRequest::getInt("id"))->comment}</h2>

    {CHtml::helpForCurrentPage()}
<script>
    jQuery(document).ready(function(){
        jQuery("#icon_selector").msDropdown();
    });
</script>

<form action="settings.php" method="post" class="form-horizontal">
        {CHtml::hiddenField("action", "addItemsForGroupsProcess")}
        {CHtml::activeHiddenField("users", $form)}

        {CHtml::errorSummary($form)}
        
    <div class="control-group">
        {CHtml::activeLabel("title", $form)}
        <div class="controls">
            {CHtml::activeTextField("title", $form)}
            {CHtml::error("title", $form)}
        </div>
    </div>
    
    <div class="control-group">
        {CHtml::activeLabel("link", $form)}
        <div class="controls">
            {CHtml::activeTextField("link", $form)}
            {CHtml::error("link", $form)}
        </div>
    </div>
    
	<div class="control-group">
        {CHtml::activeLabel("icon", $form)}
        <div class="controls">
            <select name="CDashboardItemForm[icon]" id="icon_selector">
                {foreach $icons->getItems() as $file}
                    <option value="{$file}" data-image="{$web_root}images/{$icon_theme}/16x16/{$file}">{$file}</option>
                {/foreach}
            </select>
        </div>
    </div>
    
    <div class="control-group">
        <div class="controls">
        {CHtml::submit("Сохранить", false)}
        </div>
    </div>
</form>
{/block}

{block name="asu_right"}
    {include file="_dashboard/settings/add.right.tpl"}
{/block}