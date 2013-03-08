<script>
    jQuery(document).ready(function(){
        jQuery("#icon_selector").msDropdown();
    });
</script>

<form action="index.php" method="post">
{CHtml::hiddenField("action", "save")}
{CHtml::activeHiddenField("id", $item)}
{CHtml::activeHiddenField("user_id", $item)}

    <p>{CHtml::errorSummary($item)}</p>

    <p>
        {CHtml::activeLabel("title", $item)}
        {CHtml::activeTextField("title", $item)}
        {CHtml::error("title", $item)}
    </p>
    
    <p>
        {CHtml::activeLabel("link", $item)}
        {CHtml::activeTextField("link", $item)}
        {CHtml::error("link", $item)}
    </p>   
    
    <p>
        {CHtml::activeLabel("icon", $item)}
        <select name="CDashboardItem[icon]" id="icon_selector">
            {foreach $icons->getItems() as $file}
                <option value="{$file}" data-image="{$web_root}images/tango/16x16/{$file}"
                {if $item->icon == $file}selected{/if}>{$file}</option>
            {/foreach}
        </select>
        {CHtml::error("icon", $item)}
    </p> 
    
    <p>
        {CHtml::activeLabel("parent_id", $item)}
        {CHtml::activeDropDownList("parent_id", $item, $parents->getItems())}
        {CHtml::error("parent_id", $item)}
    </p>

    <p>
        {CHtml::submit("Сохранить")}
    </p>
</form>