{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <script>
        jQuery(document).ready(function(){
        	var param = $('input[name="id1"]').val();
            jQuery("#corriculum_selector").change(function(){
                window.location.href=web_root + "_modules/_corriculum/workplans.php?action=changeCorriculum&corriculum=" + jQuery(this).val() + "&id=" + param;
            });
        });
    </script>
    <input type=hidden name=id1 value="{$plans}">
    <h2>Выбор учебного плана</h2>
    {CHtml::helpForCurrentPage()}
    
    <form action="workplans.php" method="post" enctype="multipart/form-data" class="form-horizontal">
        
		<div class="control-group">
	        Выберите учебный план
	        <div class="controls">
	            {CHtml::dropDownList("corriculums", $items, null, "corriculum_selector", "span12")}
	        </div>
	    </div>
        
    </form>
{/block}

{block name="asu_right"}
    {include file="_corriculum/_workplan/workplan/common.right.tpl"}
{/block}