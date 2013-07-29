<script>
    jQuery(document).ready(function(){
        jQuery("#icon_selector").msDropdown();
    });
</script>

<form action="index.php" method="post" class="form-horizontal">
{CHtml::hiddenField("action", "save")}
{CHtml::activeHiddenField("id", $item)}
{CHtml::activeHiddenField("user_id", $item)}

    {CHtml::errorSummary($item)}

    <div class="control-group">
        {CHtml::activeLabel("title", $item)}
        <div class="controls">
            {CHtml::activeTextField("title", $item)}
            {CHtml::error("title", $item)}
        </div>
    </div>
    
    <div class="control-group">
        {CHtml::activeLabel("link", $item)}
        <div class="controls">
            {CHtml::activeTextField("link", $item)}
            {CHtml::error("link", $item)}
        </div>
    </div>
    
    <div class="control-group">
        {CHtml::activeLabel("icon", $item)}
        <div class="controls">
            <select name="CDashboardItem[icon]" id="icon_selector">
                {foreach $icons->getItems() as $file}
                    <option value="{$file}" data-image="{$web_root}images/{$icon_theme}/16x16/{$file}"
                    {if $item->icon == $file}selected{/if}>{$file}</option>
                {/foreach}
            </select>
            {CHtml::error("icon", $item)}
        </div>
    </div>
    
    <div class="control-group">
        {CHtml::activeLabel("parent_id", $item)}
        <div class="controls">
            {CHtml::activeDropDownList("parent_id", $item, $parents->getItems())}
            {CHtml::error("parent_id", $item)}
        </div>
    </div>

    <div class="control-group">
        <div class="controls">
        {CHtml::submit("Сохранить")}
        </div>
    </div>
</form>