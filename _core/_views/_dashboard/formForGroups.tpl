<script>
    jQuery(document).ready(function(){
        jQuery("#icon_selector").msDropdown();
    });
</script>

<form action="index.php" method="post" class="form-horizontal">
{CHtml::hiddenField("action", "save")}
{CHtml::activeHiddenField("id", $item)}
{CHtml::activeHiddenField("user_id", $item)}
{CHtml::hiddenField("forGroups", CRequest::getInt("forGroups"))}

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
    
    {if ($accessLevel)}
		<div class="control-group">
		    {CHtml::activeLabel("group_id", $item)}
		    <div class="controls">
		        {CHtml::activeDropDownList("group_id", $item, $groups)}
		        {CHtml::error("group_id", $item)}
		    </div>
		</div>
		
		<div class="control-group">
		    {CHtml::activeLabel("personal_staff", $item)}
		    <div class="controls">
		        {CHtml::activeCheckBox("personal_staff", $item)}
		        {CHtml::error("personal_staff", $item)}
		    </div>
		</div>
		
		<div class="control-group">
		    {CHtml::activeLabel("personal_user", $item)}
		    <div class="controls">
		        {CHtml::activeCheckBox("personal_user", $item)}
		        {CHtml::error("personal_user", $item)}
		    </div>
		</div>
		
		<div class="control-group">
		    {CHtml::activeLabel("current_year", $item)}
		    <div class="controls">
		        {CHtml::activeCheckBox("current_year", $item)}
		        {CHtml::error("current_year", $item)}
		    </div>
		</div>
		
		<div class="control-group">
	        {CHtml::activeLabel("year_addition", $item)}
	        <div class="controls">
	            {CHtml::activeDropDownList("year_addition", $item, CTaxonomyManager::getYearsList())}
	            {CHtml::error("year_addition", $item)}
	        </div>
	    </div>
	    
	    <div class="control-group">
	        {CHtml::activeLabel("year_link", $item)}
	        <div class="controls">
	            {CHtml::activeTextField("year_link", $item)}
	            {CHtml::error("year_link", $item)}
	        </div>
	    </div>
	{/if}

    <div class="control-group">
        <div class="controls">
        {CHtml::submit("Сохранить")}
        </div>
    </div>
</form>